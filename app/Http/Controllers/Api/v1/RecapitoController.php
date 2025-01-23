<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\v1\Store\RecapitoStoreRequest;
use App\Http\Requests\v1\Update\RecapitoUpdateRequest;
use App\Http\Resources\v1\Collection\RecapitoCollection;
use App\Http\Resources\v1\CollectionComplete\RecapitoCompletoCollection;
use App\Http\Resources\v1\Resource\RecapitoResource;
use App\Http\Resources\v1\ResourceComplete\RecapitoCompletoResource;
use App\Models\Recapito;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class RecapitoController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return JsonResource
     */

    public function index()
    {

        $recapito = Recapito::all();
        $risorsa = null;
        if (request("tipo") != null && request("tipo") == "completo") {
            $risorsa = new RecapitoCompletoCollection($recapito);
        } else {
            $risorsa = new RecapitoCollection($recapito);
        }
        return $risorsa;
    }

    /**
     * Store a newly created resource in storage.
     * @param \Illuminate\Http\Requests\v1|TipologiaIndirizzoStoreRequest $request
     * @return JsonResource
     */


    public function store(RecapitoStoreRequest $request)
    {
        if (Gate::allows('creare')) {
            if (Gate::allows('Amministratore') || Gate::allows('Utente')) {
                $dati = $request->validated();
                $recapito = Recapito::create($dati);
                return new RecapitoResource($recapito);
            } else {
                abort(403, "PE_0001");
            }
        } else {
            abort(403, "PE_0002");
        }
    }

    /**
     * Display the specified resource.
     * @param \App\Models\TipologiaIndirizzo $tipologiaIndirizzo
     * @return JsonResource
     */


    public function show(Recapito $recapito)
    {

        $risorsa = null;
        if (request("tipo") != null && request("tipo") == "completo") {
            $risorsa = new RecapitoCompletoResource($recapito);
        } else {
            $risorsa = new RecapitoResource($recapito);
        }
        return $risorsa;
    }

    /**
     * Update the specified resource in storage.
     * @param \App\Models\TipologiaIndirizzo $recapito
     * @param \Illuminate\Http\Requests\v1|TipologiaIndirizzoUpdateRequest $request
     * * @return JsonResource
     */


    public function update(RecapitoUpdateRequest $request, Recapito $recapito)
    {

        if (Gate::allows('aggiornare')) {
            if (Gate::allows('Amministratore') || Gate::allows('Utente')) {
                // user is an Amministratore, allow update without restrictions
                $dati = $request->validated();
                $recapito->fill($dati);
                $recapito->save();
                return new RecapitoResource($recapito);
            } else {
                abort(403, 'PE_0001');
            }
        } else {
            abort(403, "PE_0002");
        }
    }
    /**
     * Remove the specified resource from storage.
     * @param \App\Models\TipologiaIndirizzo $recapito
     */

    public function destroy(Recapito $recapito)
    {
        if (Gate::allows('eliminare')) {
            if (Gate::allows('Amministratore') || Gate::allows('Utente')) {
                $recapito->deleteOrFail();
                return response()->noContent();
            } else {
                abort(403, 'PE_0001');
            }
        } else {
            abort(403, 'PE_0002');
        }
    }
}
