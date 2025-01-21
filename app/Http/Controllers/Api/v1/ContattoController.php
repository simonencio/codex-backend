<?php

namespace App\Http\Controllers\Api\v1;

use App\Helpers\AppHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\v1\Collection\ContattoCollection;
use App\Http\Resources\v1\CollectionComplete\ContattoCompletoCollection;
use App\Http\Resources\v1\Resource\ContattoResource;
use App\Models\Cittadinanza;
use App\Models\Comune;
use App\Models\Contatto;
use App\Models\Contatto_contattoRuolo;
use App\Models\ContattoAuth;
use App\Models\ContattoPassword;
use App\Models\ContattoSessione;
use App\Models\Indirizzo;
use App\Models\Nazione;
use App\Models\Recapito;
use App\Models\Stato;
use Firebase\JWT\JWT;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ContattoController extends Controller
{
    public function registra(Request $request)
    {
        // Valido la request
        $validator = Validator::make($request->all(), [
            'user' => 'required|string|min:5',
            'psw' => 'required|string|min:6',
            'nome' => 'required|string|between:2,100',
            'cognome' => 'required|string|between:2,100',
            'sesso' => 'required|integer|between:0,1',
            'codiceFiscale' => 'required|string|between:2,100',
            'dataNascita' => 'required|date',
            'cittaNascita' => 'required|string',
            'cittadinanza' => 'required|string',
            'nazione' => 'required|string',
            'citta' => 'required|string', // Assuming 'comune' is the name of the city
            'provincia' => 'required|string', // Assuming province is a string
            "cap" => 'required|integer',
            'ruolo' => 'required|string',
            'partitaIva' => 'integer|null',
            'tipologiaIndirizzo' => 'required|integer',
            'indirizzo' => 'required|string',
            'civico' => 'required|integer',
            'altro' => 'string|null',
            'recapito' => 'required|string',
            'idTipoRecapito' => 'required|integer'
        ]);

        // se fallisce la validazione ritorno un'errore
        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }

        // controllo se esiste già un'utente 
        $existingUser   = ContattoAuth::where('user', $request->input('user'))->first();

        // hash the provided password
        $hashedPassword = AppHelper::nascondiPassword($request->input('psw'), Str::random(200));

        // controllo se esiste già una password con lo stesso hash
        $existingPsw = ContattoPassword::where('psw', $hashedPassword)->first();

        // se esiste ritorno un'errore
        if ($existingUser) {
            return response()->json(['error' => 'User already exists'], 400);
        }
        if ($existingPsw) {
            return response()->json(['error' => 'Psw already exists'], 400);
        }

        // Get the selected values from the request
        $nazione = $request->input('nazione');
        $cittadinanza = $request->input('cittadinanza'); // Assuming this is the correct field
        $citta = $request->input('citta');
        $provincia = $request->input('provincia');
        $cap = $request->input('cap');
        $tipologiaIndirizzo = 1; // Set this to the appropriate value based on your application logic
        $indirizzo = $request->input('indirizzo'); // Assuming you have an 'indirizzo' field in your request
        $civico = $request->input('civico'); // Assuming you have a 'civico' field in your request
        $altro = $request->input('altro');
        // Validate the city and province against the Comune table
        // $cittadinanza = Cittadinanza::where('nome', $cittadinanza)->first();
        // if (!$cittadinanza) {
        //     return response()->json(['error' => 'Invalid cittadinanza selected'], 400);
        // }
        // $nazione = Nazione::where('nazione', $nazione)->first();
        // if (!$nazione) {
        //     return response()->json(['error' => 'Invalid Nazione selected'], 400);
        // }
        // $citta = Comune::where('comune', $citta)->first();
        // if (!$citta) {
        //     return response()->json(['error' => 'Invalid city selected'], 400);
        // }
        // $provincia = Comune::where('provincia',  $provincia)->first();
        // if (!$provincia) {
        //     return response()->json(['error' => 'Invalid city selected'], 400);
        // }
        // $capInizio = Comune::where('capInizio', $cap)->first();
        // if (!$capInizio) {
        //     return response()->json(['error' => 'Invalid city selected'], 400);
        // }

        // Get the default idStato from the stati table
        $idStato = Stato::where('idStato', true)->first()->idStato; // Adjust this line as needed

        // Se tutto è corretto creo un oggetto con i dati validati
        $contatto = Contatto::create(array_merge(
            $validator->validated(),
            [
                'idStato' => $idStato
            ],
        ));
        $recapito = Recapito::create([
            'recapito' => $request->input('recapito'),
            'idTipoRecapito' => $request->input('idTipoRecapito'),
            'idContatto' => $contatto->idContatto, // Link to the created Contatto
        ]);

        $indirizzo = Indirizzo::create(array_merge(
            $validator->validated(),
            [
                'idTipologiaIndirizzo' => $tipologiaIndirizzo,
                'idContatto' => $contatto->idContatto,
                'nazione' => $nazione,
                'cittadinanza' => $cittadinanza,
                'provincia' => $provincia,
                'citta' => $citta,
                'cap' => $cap,
                'indirizzo' => $indirizzo,
                'civico' => $civico,
                'altro' => $altro,
            ],
        ));
        // $indirizzo = new Indirizzo();
        // $indirizzo->idTipologiaIndirizzo = $tipologiaIndirizzo;
        // $indirizzo->idContatto = $contatto->idContatto;
        // $indirizzo->nazione = $nazione;
        // $indirizzo->cittadinanza = $cittadinanza;
        // $indirizzo->provincia = $provincia;
        // $indirizzo->citta = $citta;
        // $indirizzo->cap = $cap;
        // $indirizzo->indirizzo = $indirizzo;
        // $indirizzo->civico = $civico;
        // $indirizzo->altro = $altro;
        // creo un sale casuale
        $sale = AppHelper::nascondiPassword($request->password, Str::random(200));

        // creo un oggetto in contattiPassword con la password criptata ed il sale
        $contattoPassword = new ContattoPassword([
            'psw' => $request->psw,
            "sale" => $sale
        ]);

        // genero una sfida casuale 
        $sfida = hash("sha512", trim(Str::random(200)));
        $inizioSfida = time();
        $obbligoCampo = 1;

        // creo il payload per il token JWT
        $payload = [
            'user' => $request->user,
            'psw' => $sale,
        ];

        // creo il token JWT usando il payload e la secretKey
        $secretKey = 'sha512';
        $secretJWT = JWT::encode($payload, $secretKey, 'HS256');

        // creo un oggetto in contattoAuth con il token ed altri dati
        $contattoAuth = new ContattoAuth();
        $contattoAuth->user = $request->user;
        $contattoAuth->idContatto = $contatto->idContatto;
        $contattoAuth->sfida = $sfida;
        $contattoAuth->secretJWT = $secretJWT;
        $contattoAuth->inizioSfida = $inizioSfida;
        $contattoAuth->obbligoCampo = $obbligoCampo;

        // creo un oggetto in contattoSessione con il token ed altri dati
        $contattoSessione = new ContattoSessione();
        $contattoSessione->idContatto = $contatto->idContatto;



        // creo un payload per la sezione token di contattoSessione
        // $payload = [
        //     'user' => $request->user,
        //     'psw' => $sale,
        // ];

        // $secretKey = 'sha512';
        // $token = JWT::encode($payload, $secretKey, 'HS256');


        // inserisco il token in ContattoSessione
        $contattoSessione->token = $secretJWT;
        $contattoSessione->inizioSessione = time();

        // collego i suoli a numeri
        $roleMapping = [
            'Amministratore' => 1,
            'Utente' => 2,
            'Ospite' => 3,
        ];
        $ruolo = $request->input('ruolo');

        // controllo se il ruolo è valido e creo un oggetto in contatto_contattoRuolo

        if (isset($roleMapping[$ruolo])) {
            $contattoContattoRuolo = new Contatto_contattoRuolo();
            $contattoContattoRuolo->idContatto = $contatto->idContatto;
            $contattoContattoRuolo->idContattoRuolo = $roleMapping[$ruolo];
            $contattoContattoRuolo->save();
        } else { // se il  ruolo non esiste mando un errore
            abort(403, "Invalid_role");
        }



        // salvo tutti i dati nel database
        $contattoSessione->save();
        $contattoAuth->save();
        $contatto->contattoPassword()->save($contattoPassword);
        $indirizzo->save();

        // ritorno una risposta positiva se tutto è creato correttamente
        return response()->json([
            'message' => 'User  successfully registered',
            'contatto' => $contatto,
            'contatto_password' => $contattoPassword,
            'contattoAuth' => $contattoAuth,
            'ContattoSessione' => $contattoSessione,
            'Indirizzo' => $indirizzo,
            'recapito' => $recapito,

        ], 201);
    }
    public function checkUser(Request $request)
    {
        // Validate the request
        $validator = Validator::make($request->all(), [
            'user' => 'required|string|min:5',
        ]);

        // If validation fails, return an error
        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }

        // Retrieve the user from the request
        $username = $request->input('user');

        // Check if the user already exists
        $existingUser  = ContattoAuth::where('user', $username)->first();
        if ($existingUser) {
            return response()->json(['error' => 'Email già in uso'], 400);
        }

        // If the user does not exist, return a success response
        return response()->json(['message' => 'Email disponibile'], 200);
    }
    public function index()
    {

        $contatto = Contatto::all();
        $risorsa = null;
        if (request("tipo") != null && request("tipo") == "completo") {
            $risorsa = new ContattoCompletoCollection($contatto);
        } else {
            $risorsa = new ContattoCollection($contatto);
        }
        return $risorsa;
    }
}
