<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\v1\Store\SerieTv_ContattiStoreRequest;
use App\Http\Requests\v1\Update\SerieTv_ContattiUpdateRequest;
use App\Http\Resources\v1\Collection\SerieTv_ContattiCollection;
use App\Http\Resources\v1\CollectionComplete\SerieTv_ContattiCompletoCollection;
use App\Http\Resources\v1\Resource\SerieTv_ContattiResource;
use App\Http\Resources\v1\ResourceComplete\SerieTv_ContattiCompletoResource;
use App\Models\SerieTvContatti;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class SerieTvContattiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (Gate::allows('leggere')) {
            if (Gate::allows('Amministratore') || Gate::allows('Utente') || Gate::allows('Ospite')) {
                $serieTvContatto = SerieTvContatti::all();
                $risorsa = null;
                if (request("tipo") != null && request("tipo") == "completo") {
                    $risorsa = new SerieTv_ContattiCompletoCollection($serieTvContatto);
                } else {
                    $risorsa = new SerieTv_ContattiCollection($serieTvContatto);
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
     * Store a newly created resource in storage.
     *  * @param \Illuminate\Http\Requests\v1\IndirizzoStoreRequest $request
     * @return JsonResponse
     */
    public function store(SerieTv_ContattiStoreRequest $request)
    {
        if (Gate::allows('creare')) {
            if (Gate::allows('Amministratore')) {
                $dati = $request->validated();
                $serieTvContatto = SerieTvContatti::create($dati);
                return new SerieTv_ContattiResource($serieTvContatto);
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
    public function show(SerieTvContatti $serieTvContatto)
    {
        if (Gate::allows('leggere')) {
            if (Gate::allows('Amministratore') || Gate::allows('Utente')) {
                $risorsa = null;
                if (request("tipo") != null && request("tipo") == "completo") {
                    $risorsa = new SerieTv_ContattiCompletoResource($serieTvContatto);
                } else {
                    $risorsa = new SerieTv_ContattiResource($serieTvContatto);
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
    public function update(SerieTv_ContattiUpdateRequest $request, SerieTvContatti $serieTvContatto)
    {

        if (Gate::allows('aggiornare')) {
            if (Gate::allows('Amministratore')) {
                $dati = $request->validated();
                $serieTvContatto->fill($dati);
                $serieTvContatto->save();
                return new SerieTv_ContattiResource($serieTvContatto);
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
    public function destroy(SerieTvContatti $serieTvContatto)
    {
        if (Gate::allows('eliminare')) {
            if (Gate::allows('Amministratore')) {
                $serieTvContatto->deleteOrFail();
                return response()->noContent();
            } else {
                abort(403, 'PE_0001');
            }
        } else {
            abort(403, 'PE_0002');
        }
    }
}
