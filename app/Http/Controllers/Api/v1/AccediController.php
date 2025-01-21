<?php

namespace App\Http\Controllers\Api\v1;

use App\Helpers\AppHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\v1\Resource\contatto_contattoRuoloResource;
use App\Models\Configurazione;
use App\Models\Contatto_contattoRuolo;
use App\Models\ContattoAccesso;
use App\Models\ContattoAuth;
use App\Models\ContattoPassword;
use App\Models\ContattoSessione;
use Illuminate\Http\Request;
use Illuminate\Support\Str;


class AccediController extends Controller
{
    //- PUBLIC --------------------------------------------------------------------------
    //-----------------------------------------------------------------------------------

    /**
     * cerco l'hash dello user nel DB
     * @param string $utente
     * @param string $hash
     * @return AppHelper\ritornoCustom
     */
    public function searchMail($utente)
    {
        $tmp = (ContattoAuth::esisteUtente($utente)) ? true : false;
        return AppHelper::rispostaCustom($tmp);
    }

    //---------------------------------------------------------------------------------------
    /**
     * punto di ingresso del login
     * @param string $utente
     * @param string $hash
     * @return AppHelper\ritornoCustom
     */
    public function show($utente, $hash = null)
    {
        $ruolo = Contatto_contattoRuolo::where('idContattoRuolo');
        if ($hash == null) {
            return AccediController::controlloUtente($utente);
        } else {
            return AccediController::controlloPassword($utente, $hash);
            return $ruolo;
        }
    }

    //---------------------------------------------------------------------------------------
    /**
     * creo il token per lo sviluppo
     * 
     * @return AppHelper\rispostaCustom
     */
    public static function testToken()
    {
        $utente = hash("sha512", trim("Admin@Utente"));
        $password = hash("sha512", trim("Password123!"));
        $sale = hash("sha512", trim("Sale"));
        $sfida = hash("sha512", trim("Sfida"));
        $secretJWT = hash("sha512", trim("Secret"));
        $auth = ContattoAuth::where('user', $utente)->firstOrFail();
        if ($auth != null) {
            $auth->inizioSfida = time();
            $auth->secretJWT = $secretJWT;
            $auth->save();

            $recordPassword = ContattoPassword::passwordAttuale($auth->idContatto);
            if ($recordPassword != null) {
                $recordPassword->sale = $sale;
                $recordPassword->psw = $password;
                $recordPassword->save();
                $cipher = AppHelper::nascondiPassword($password, $sale);
                $tk = AppHelper::creaTokenSessione($auth->idContatto, $secretJWT);
                $dati = array("token" => $tk, "Login" => $cipher);
                $sessione = ContattoSessione::where("idContatto", $auth->idContatto)->firstOrFail();
                $sessione->token = $tk;
                $sessione->inizioSessione = time();
                $sessione->save();
                return AppHelper::rispostaCustom($dati);
            }
        }
    }

    //---------------------------------------------------------------------------------------
    /**
     * crea il token per sviluppo
     * 
     * @param string $utente
     * @return AppHelper\rispostaCustom
     */

    public static function testLogin($hashUtente, $hashPassword)
    {
        print_r(AccediController::controlloPassword($hashUtente, $hashPassword));
    }

    //---------------------------------------------------------------------------------------
    /**
     * verifica il token ad ogni chiamata
     * @param string $token
     * @return object
     */
    public static function verificaToken($token)
    {
        $rit = null;
        $sessione = ContattoSessione::datiSessione($token);
        if ($sessione != null) {
            $inizioSessione = $sessione->inizioSessione;
            $durataSessione = Configurazione::leggiValore("durataSessione");
            $scadenzaSessione = $inizioSessione + $durataSessione;
            if (
                time() < $scadenzaSessione
            ) {
                $auth = ContattoAuth::where('idContatto', $sessione->idContatto)->first();
                if ($auth != null) {
                    $secretJWT = $auth->secretJWT;
                    $payload = AppHelper::validaToken($token, $secretJWT, $sessione);
                    if ($payload != null) {
                        $rit = $payload;
                    } else {
                        abort(403, 'TK_0006');
                    }
                } else {
                    abort(403, 'TK_0005');
                }
            } else {
                abort(403, 'TK_0004');
            }
        } else {
            abort(403, 'TK_0003');
        }
        return $rit;
    }

    // -PROTECTED------------------------------------------------------------------------------------------------------
    //------------------------------------------------------------------------------------------------------------------
    /**
     * controllo validita utente
     * 
     *@param string $utente
     * @return AppHelper\rispostaCustom
     */

    protected static function controlloUtente($utente)
    {
        $sale = hash("sha512", trim(Str::random(200)));
        // $sale = hash("sha512", "ciao");
        if (ContattoAuth::esisteUtenteValidoPerLogin($utente)) {
            $auth = ContattoAuth::where('user', $utente)->first();
            $auth->secretJWT = hash("sha512", trim(Str::random(200)));
            $auth->inizioSfida = time();
            $auth->save();
            $recordPassword = ContattoPassword::passwordAttuale($auth->idContatto);
            $recordPassword->sale = $sale;
            $recordPassword->save();
        } else {
        }
        $dati = array("sale" => $sale);
        return AppHelper::rispostaCustom($dati);
    }

    //------------------------------------------------------------------------------------------------------
    /**
     * punto di ingresso del login
     * 
     * @param string $utente
     * @param string $hash
     * @return AppHelper\rispostaCustom
     */
    protected static function controlloPassword($utente, $hashClient)
    {
        if (ContattoAuth::esisteUtenteValidoPerLogin($utente)) {
            $auth = ContattoAuth::where('user', $utente)->first();
            $secretJWT = $auth->secretJWT;
            $inizioSfida = $auth->inizioSfida;
            $durataSfida = Configurazione::leggiValore("durataSfida");
            $maxTentativi = Configurazione::leggiValore("maxLoginErrati");
            $scadenzaSfida = $inizioSfida + $durataSfida;

            if (time() < $scadenzaSfida) {
                $tentativi = ContattoAccesso::contaTentativi($auth->idContatto);
                if ($tentativi < $maxTentativi - 1) {
                    $recordPassword = ContattoPassword::passwordAttuale($auth->idContatto);
                    $password = $recordPassword->psw;
                    $sale = $recordPassword->sale;

                    $passwordNascostaDB = AppHelper::nascondiPassword($password, $sale);


                    if ($passwordNascostaDB == $hashClient) {
                        $tk = AppHelper::creaTokenSessione($auth->idContatto, $secretJWT);
                        ContattoAccesso::eliminaTentativi($auth->idContatto);
                        $accesso = ContattoAccesso::aggiungiAccesso($auth->idContatto);

                        ContattoSessione::eliminaSessione($auth->idContatto);

                        ContattoSessione::aggiornaSessione($auth->idContatto, $tk);

                        $dati = array("tk" => $tk);
                        return AppHelper::rispostaCustom($dati);
                    } else {
                        ContattoAccesso::aggiungiTentativoFallito($auth->idContatto);
                        abort(403, "ERR L004");
                    }
                } else {
                    abort(403, "ERR L003");
                }
            } else {
                ContattoAccesso::aggiungiTentativoFallito($auth->idContatto);
                abort(403, "ERR L002");
            }
        } else {
            abort(403, "ERR L001");
        }
    }

    public static function cifraUser(Request $request)
    {
        $nome = $request->input('nome');

        $user_data = $nome;
        $hashed_user = hash("sha512", $user_data);
        return $hashed_user;
    }
    public static function cifraPassword(Request $request)
    {
        $psw = $request->input('psw');
        $hashed_psw = hash("sha512", $psw);
        return $hashed_psw;
    }
}
