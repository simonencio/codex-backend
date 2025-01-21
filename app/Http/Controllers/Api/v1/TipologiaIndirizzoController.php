<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\v1\Store\TipologiaIndirizzoStoreRequest;
use App\Http\Requests\v1\Update\TipologiaIndirizzoUpdateRequest;
use App\Http\Resources\v1\Collection\TipologiaIndirizzoCollection;
use App\Http\Resources\v1\CollectionComplete\TipologiaIndirizzoCompletoCollection;
use App\Http\Resources\v1\Resource\TipologiaIndirizzoResource;
use App\Http\Resources\v1\ResourceComplete\TipologiaIndirizzoCompletoResource;
use App\Models\TipologiaIndirizzo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class TipologiaIndirizzoController extends Controller
{


    /**
     * Display a listing of the resource.
     * @return JsonResource
     */

    public function index()
    {

        $tipologiaIndirizzo = TipologiaIndirizzo::all();
        $risorsa = null;
        if (request("tipo") != null && request("tipo") == "completo") {
            $risorsa = new TipologiaIndirizzoCompletoCollection($tipologiaIndirizzo);
        } else {
            $risorsa = new TipologiaIndirizzoCollection($tipologiaIndirizzo);
        }
        return $risorsa;
    }


    /**
     * Store a newly created resource in storage.
     * @param \Illuminate\Http\Requests\v1|TipologiaIndirizzoStoreRequest $request
     * @return JsonResource
     */

    public function store(TipologiaIndirizzoStoreRequest $request)
    {
        if (Gate::allows('creare')) {
            if (Gate::allows('Amministratore')) {
                $dati = $request->validated();
                $tipologiaIndirizzo = TipologiaIndirizzo::create($dati);
                return new TipologiaIndirizzoResource($tipologiaIndirizzo);
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



    public function show(TipologiaIndirizzo $tipologiaIndirizzo)
    {
        if (Gate::allows('leggere')) {
            if (Gate::allows('Amministratore') || Gate::allows('Utente')) {
                $risorsa = null;
                if (request("tipo") != null && request("tipo") == "completo") {
                    $risorsa = new TipologiaIndirizzoCompletoResource($tipologiaIndirizzo);
                } else {
                    $risorsa = new TipologiaIndirizzoResource($tipologiaIndirizzo);
                }
                return $risorsa;
            } else {
                abort(403, 'PE_0001');
            }
        } else {
            abort(403, 'PE_0002');
        }
    }

    /**
     * Update the specified resource in storage.
     * @param \App\Models\TipologiaIndirizzo $tipologiaIndirizzo
     * @param \Illuminate\Http\Requests\v1|TipologiaIndirizzoUpdateRequest $request
     * * @return JsonResource
     */


    public function update(TipologiaIndirizzoUpdateRequest $request, TipologiaIndirizzo $tipologiaIndirizzo)
    {

        if (Gate::allows('aggiornare')) {
            if (Gate::allows('Amministratore')) {
                $dati = $request->validated();
                $tipologiaIndirizzo->fill($dati);
                $tipologiaIndirizzo->save();
                return new TipologiaIndirizzoResource($tipologiaIndirizzo);
            } else {
                abort(403, 'PE_0001');
            }
        } else {
            abort(403, "PE_0002");
        }
    }

    /**
     * Remove the specified resource from storage.
     * @param \App\Models\TipologiaIndirizzo $tipologiaIndirizzo
     */


    public function destroy(TipologiaIndirizzo $tipologiaIndirizzo)
    {
        if (Gate::allows('eliminare')) {
            if (Gate::allows('Amministratore')) {
                $tipologiaIndirizzo->deleteOrFail();
                return response()->noContent();
            } else {
                abort(403, 'PE_0001');
            }
        } else {
            abort(403, 'PE_0002');
        }
    }
}
