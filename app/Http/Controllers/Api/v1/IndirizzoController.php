<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\v1\Store\IndirizzoStoreRequest;
use App\Http\Requests\v1\Update\IndirizzoUpdateRequest;
use App\Http\Resources\v1\Collection\IndirizzoCollection;
use App\Http\Resources\v1\CollectionComplete\IndirizzoCompletoCollection;
use App\Http\Resources\v1\Resource\IndirizzoResource;
use App\Http\Resources\v1\ResourceComplete\IndirizzoCompletoResource;
use App\Models\Indirizzo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class IndirizzoController extends Controller
{




    /**
     * Display a listing of the resource.
     * @return JsonResource
     */


    public function index()
    {

        $indirizzo = Indirizzo::all();
        $risorsa = null;
        if (request("tipo") != null && request("tipo") == "completo") {
            $risorsa = new IndirizzoCompletoCollection($indirizzo);
        } else {
            $risorsa = new IndirizzoCollection($indirizzo);
        }
        return $risorsa;
    }

    /**
     * Store a newly created resource in storage.
     *  * @param \Illuminate\Http\Requests\v1\IndirizzoStoreRequest $request
     * @return JsonResponse
     */
    public function store(IndirizzoStoreRequest $request)
    {
        if (Gate::allows('creare')) {
            if (Gate::allows('Amministratore') || Gate::allows('Utente')) {
                $dati = $request->validated();
                $indirizzo = Indirizzo::create($dati);
                return new IndirizzoResource($indirizzo);
            } else {
                abort(403, "PE_0001");
            }
        } else {
            abort(403, "PE_0002");
        }
    }

    /**
     * Display the specified resource.
     * @param \App\Models\Indirizzo
     * @return JsonResource
     */
    public function show(Indirizzo $indirizzo)
    {
        if (Gate::allows('leggere')) {
            if (Gate::allows('Amministratore') || Gate::allows('Utente')) {
                $risorsa = null;
                if (request("tipo") != null && request("tipo") == "completo") {
                    $risorsa = new IndirizzoCompletoResource($indirizzo);
                } else {
                    $risorsa = new IndirizzoResource($indirizzo);
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
     * * @param \Illuminate\Http\Requests\v1\IndirizzoUpdateRequest $request
     * @param Indirizzo $indirizzo
     *  @return JsonResource
     */
    public function update(IndirizzoUpdateRequest $request, Indirizzo $indirizzo)
    {

        if (Gate::allows('aggiornare')) {
            if (
                Gate::allows('Amministratore') || Gate::allows('Utente')
            ) {
                // user is an Amministratore, allow update without restrictions
                $dati = $request->validated();
                $indirizzo->fill($dati);
                $indirizzo->save();
                return new IndirizzoResource($indirizzo);
            } else {
                abort(403, 'PE_0001');
            }
        } else {
            abort(403, "PE_0002");
        }
    }

    /**
     * Remove the specified resource from storage.
     * @param \App\Models\Indirizzo $tipologiaIndirizzo
     */
    public function destroy(Indirizzo $indirizzo)
    {
        if (Gate::allows('eliminare')) {
            if (Gate::allows('Amministratore') || Gate::allows('Utente')) {
                $indirizzo->deleteOrFail();
                return response()->noContent();
            } else {
                abort(403, 'PE_0001');
            }
        } else {
            abort(403, 'PE_0002');
        }
    }
}
