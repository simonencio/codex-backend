<?php

use App\Helpers\AppHelper;
use App\Http\Controllers\Api\v1\AccediController;
use App\Http\Controllers\Api\v1\CategoriaController;
use App\Http\Controllers\Api\v1\CittadinanzaController;
use App\Http\Controllers\Api\v1\ComuneController;
use App\Http\Controllers\Api\v1\ContattoController;
use App\Http\Controllers\Api\v1\CreditoController;
use App\Http\Controllers\Api\v1\EpisodioController;
use App\Http\Controllers\Api\v1\FilmContattiController;
use App\Http\Controllers\Api\v1\FilmController;
use App\Http\Controllers\Api\v1\IndirizzoController;
use App\Http\Controllers\Api\v1\LinguaController;
use App\Http\Controllers\Api\v1\NazioneController;
use App\Http\Controllers\Api\v1\ProfiloController;
use App\Http\Controllers\Api\v1\RecapitoController;
use App\Http\Controllers\Api\v1\SerieTvContattiController;
use App\Http\Controllers\Api\v1\SerieTvController;
use App\Http\Controllers\Api\v1\TipologiaIndirizzoController;
use App\Http\Controllers\Api\v1\TipoRecapitoController;
use App\Http\Controllers\Api\v1\UploadController;
use Illuminate\Support\Facades\Route;

if (!defined('_VERS')) {
    define('_VERS', 'v1');
}
//----------------------------------------------------------------------------------------------------------------------------------------------------------
//----------------------------------------------------------------------------------------------------------------------------------------------------------
//----------------------------------------------------------------------------------------------------------------------------------------------------------
Route::get(_VERS . '/testLogin', function () {
    // modificare i dati seguenti in base a quelli inseriti nel database, specialmente il SALE!
    $hashUser = "9b5012de69fc2d55c17675d6fdc83af3d3b9f98b739e01d78e2f416c8ee773ab5ead640e136813c88bab125f0d667d50a7f0c780f45e44a7008b2bfd138e1854";
    $psw = "619050b28071e5e937f55ef0c7623fe0ef209de331f4067700231174507722ddedc592134fef85aedd6f41d48907e13e4e10cb77f1b7abf5d9e3e1f2194547a4";
    $sale  = "4610931c2d8f233bbd1dd05db80705958d337317b9d2a025559bcd8aa83a6f7b8e42af260e40f054d38ab7b02b97a7c2365cc81798b27b928081ce83c6b6c753";
    // rimuovere testLogin e  in controlloUtente il sale diventa random
    $hashSalePsw = AppHelper::nascondiPassword($psw, $sale);

    AccediController::testLogin($hashUser, $hashSalePsw);
});
//----------------------------------------------------------------------------------------------------------------------------------------------------------
//----------------------------------------------------------------------------------------------------------------------------------------------------------
//----------------------------------------------------------------------------------------------------------------------------------------------------------
Route::post(_VERS . '/cifra', [AccediController::class, 'cifraUser']);
Route::post(_VERS . '/cifraPsw', [AccediController::class, 'cifraPassword']);
Route::get(_VERS . '/accedi/{utente}/{hash?}', [AccediController::class, 'show']);
Route::post(_VERS . "/registrazione/", [ContattoController::class, 'registra']);
Route::get(_VERS . "/searchMail/{user}", [AccediController::class, 'searchMail']);
Route::post('/check-user', [ContattoController::class, 'checkUser']);
Route::get(_VERS . "/nazioni", [NazioneController::class, 'index']);
Route::get(_VERS . "/cittadinanze", [CittadinanzaController::class, 'index']);
Route::get(_VERS . "/comuni", [ComuneController::class, 'index']);
Route::get(_VERS . "/tipologiaIndirizzi", [TipologiaIndirizzoController::class, 'index']);
Route::get(_VERS . "/contatti", [ContattoController::class, 'index']);
Route::get(_VERS . "/indirizzi", [IndirizzoController::class, 'index']);
Route::get(_VERS . "/episodi", [EpisodioController::class, 'index']);
Route::get(_VERS . "/tipirecapiti", [TipoRecapitoController::class, 'index']);
Route::get(_VERS . "/recapiti", [RecapitoController::class, 'index']);



//----------------------------------------------------------------------------------------------------------------------------------------------------------
//----------------------------------------------------------------------------------------------------------------------------------------------------------
//----------------------------------------------------------------------------------------------------------------------------------------------------------
Route::middleware(["Autenticazione", "ContattoRuolo:Amministratore,Utente,Ospite"])->group(
    function () {
        Route::get(_VERS . "/categorie", [CategoriaController::class, 'index']);
        Route::get(_VERS . "/profili", [ProfiloController::class, 'index']);
        Route::get(_VERS . "/crediti", [CreditoController::class, 'index']);

        Route::get(_VERS . "/lingue", [LinguaController::class, 'index']);
        Route::get(_VERS . "/serietv", [SerieTvController::class, 'index']);
        Route::get(_VERS . "/film", [FilmController::class, 'index']);
        Route::get(_VERS . "/serietvContatti", [SerieTvContattiController::class, 'index']);
        Route::get(_VERS . "/filmContatti", [FilmContattiController::class, 'index']);
    }
);
Route::middleware(['Autenticazione', "ContattoRuolo:Amministratore,Utente"])->group(
    function () {
        Route::get(_VERS . "/nazioni/{nazione}", [NazioneController::class, 'show']);
        Route::get(_VERS . "/comuni/{comune}", [ComuneController::class, 'show']);
        Route::get(_VERS . "/cittadinanze/{cittadinanza}", [CittadinanzaController::class, 'show']);
        Route::get(_VERS . "/tipologiaIndirizzi/{tipologiaIndirizzo}", [TipologiaIndirizzoController::class, 'show']);
        Route::get(_VERS . "/categorie/{categoria}", [CategoriaController::class, 'show']);
        Route::get(_VERS . "/indirizzi/{indirizzo}", [IndirizzoController::class, 'show']);
        Route::get(_VERS . "/profili/{profilo}", [ProfiloController::class, 'show']);
        Route::get(_VERS . "/crediti/{credito}", [CreditoController::class, 'show']);
        Route::get(_VERS . "/recapiti/{recapito}", [RecapitoController::class, 'show']);
        Route::get(_VERS . "/tipirecapiti/{tipoRecapito}", [TipoRecapitoController::class, 'show']);
        Route::get(_VERS . "/lingue/{lingua}", [LinguaController::class, 'show']);
        Route::get(_VERS . "/serietv/{serieTv}", [SerieTvController::class, 'show']);
        Route::get(_VERS . "/film/{film}", [FilmController::class, 'show']);
        Route::get(_VERS . "/episodi/{episodio}", [EpisodioController::class, 'show']);
        Route::get(_VERS . "/serietvContatti/{serieTvContatto}", [SerieTvContattiController::class, 'show']);
        Route::get(_VERS . "/filmContatti/{filmContatto}", [FilmContattiController::class, 'show']);
        Route::get(_VERS . "/nazioni/continente/{continente}", [NazioneController::class, 'indexContinente']);
        Route::get(_VERS . "/comuni/regione/{regione}", [ComuneController::class, 'indexRegione']);
        Route::get(_VERS . "/comuni/provincia/{provincia}", [ComuneController::class, 'indexProvincia']);
        Route::get(_VERS . "/cittadinanze/nome/{nome}", [CittadinanzaController::class, 'indexCittadinanza']);


        Route::post(_VERS . "/indirizzi", [IndirizzoController::class, 'store']);  //fatto
        Route::put(_VERS . "/indirizzi/{indirizzo}", [IndirizzoController::class, 'update']);  //fatto
        Route::delete(_VERS . "/indirizzi/{indirizzo}", [IndirizzoController::class, 'destroy']);  //fatto

        Route::post(_VERS . "/profili", [ProfiloController::class, 'store']);  //fatto
        Route::put(_VERS . '/profili/{profilo}', [ProfiloController::class, 'update']);  //fatto
        Route::delete(_VERS . '/profili/{profilo}', [ProfiloController::class, 'destroy']);  //fatto

        Route::post(_VERS . "/crediti", [CreditoController::class, 'store']);  //fatto
        Route::put(_VERS . '/crediti/{credito}', [CreditoController::class, 'update']);  //fatto
        Route::delete(_VERS . '/crediti/{credito}', [CreditoController::class, 'destroy']);  //fatto

        Route::post(_VERS . "/recapiti", [RecapitoController::class, 'store']);  //fatto
        Route::put(_VERS . '/recapiti/{recapito}', [RecapitoController::class, 'update']);  //fatto
        Route::delete(_VERS . '/recapiti/{recapito}', [RecapitoController::class, 'destroy']);  //fatto
    }
);
Route::middleware(['Autenticazione', "ContattoRuolo:Amministratore"])->group(
    function () {

        Route::post(_VERS . "/serietvContatti", [SerieTvContattiController::class, 'store']);  //fatto
        Route::put(_VERS . "/serietvContatti/{serieTvContatto}", [SerieTvContattiController::class, 'update']);  //fatto
        Route::delete(_VERS . "/serietvContatti/{serieTvContatto}", [SerieTvContattiController::class, 'destroy']);  //fatto


        Route::post(_VERS . "/filmContatti", [FilmContattiController::class, 'store']);  //fatto
        Route::put(_VERS . "/filmContatti/{filmContatto}", [FilmContattiController::class, 'update']);  //fatto
        Route::delete(_VERS . "/filmContatti/{filmContatto}", [FilmContattiController::class, 'destroy']);  //fatto

        // fatto

        Route::post(_VERS . "/tipirecapiti", [TipoRecapitoController::class, 'store']);  //fatto
        Route::put(_VERS . '/tipirecapiti/{tipoRecapito}', [TipoRecapitoController::class, 'update']);  //fatto
        Route::delete(_VERS . '/tipirecapiti/{tipoRecapito}', [TipoRecapitoController::class, 'destroy']);  //fatto

        Route::post(_VERS . "/tipologiaIndirizzi", [TipologiaIndirizzoController::class, 'store']); //fatto
        Route::put(_VERS . "/tipologiaIndirizzi/{tipologiaIndirizzo}", [TipologiaIndirizzoController::class, 'update']); //fatto
        Route::delete(_VERS . "/tipologiaIndirizzi/{tipologiaIndirizzo}", [TipologiaIndirizzoController::class, 'destroy']); //fatto

        Route::post(_VERS . "/categorie", [CategoriaController::class, 'store']); // fatto
        Route::put(_VERS . '/categorie/{categoria}', [CategoriaController::class, 'update']); // fatto
        Route::delete(_VERS . '/categorie/{categoria}', [CategoriaController::class, 'destroy']); // fatto

        Route::post(_VERS . "/serietv", [SerieTvController::class, 'store']); // fatto

        Route::put(_VERS . "/serietv/{serieTv}", [SerieTvController::class, 'update']); //fatto
        Route::delete(_VERS . "/serietv/{serieTv}", [SerieTvController::class, 'destroy']); // fatto

        Route::post(_VERS . "/film", [FilmController::class, 'store']); // fatto
        Route::put(_VERS . "/film/{film}", [FilmController::class, 'update']); // fatto
        Route::delete(_VERS . "/film/{film}", [FilmController::class, 'destroy']); // fatto

        Route::post(_VERS . "/episodi", [EpisodioController::class, 'store']); // fatto
        Route::put(_VERS . "/episodi/{episodio}", [EpisodioController::class, 'update']); //fatto
        Route::delete(_VERS . "/episodi/{episodio}", [EpisodioController::class, 'destroy']); // fatto

        Route::delete(_VERS . '/nazioni/{nazione}', [NazioneController::class, 'destroy']);  //fatto
        Route::delete(_VERS . '/comuni/{comune}', [ComuneController::class, 'destroy']);  //fatto
        Route::delete(_VERS . '/cittadinanze/{cittadinanza}', [CittadinanzaController::class, 'destroy']);  //fatto
        Route::post(_VERS . "/uploadFilm", [UploadController::class, 'indexFilm']);
        Route::post(_VERS . "/uploadSerieTv", [UploadController::class, 'indexSerieTv']);
        Route::post(_VERS . "/uploadEpisodi", [UploadController::class, 'indexEpisodi']);
        // Route::post(_VERS . "/lingue", [LinguaController::class, 'store']);
        // Route::put(_VERS . "/lingue/{lingua}", [LinguaController::class, 'update']);
        // Route::delete(_VERS . "/lingue/{lingua}", [LinguaController::class, 'destroy']);
    }
);
