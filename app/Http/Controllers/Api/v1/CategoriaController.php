<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\v1\Store\CategoriaStoreRequest;
use App\Http\Requests\v1\Update\CategoriaUpdateRequest;
use App\Http\Resources\v1\Collection\CategoriaCollection;
use App\Http\Resources\v1\CollectionComplete\CategoriaCompletoCollection;
use App\Http\Resources\v1\Resource\CategoriaResource;
use App\Http\Resources\v1\ResourceComplete\CategoriaCompletoResource;
use App\Models\Categoria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class CategoriaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResource
     */


    public function index()
    {
        if (Gate::allows('leggere')) {
            if (Gate::allows('Amministratore') || Gate::allows('Utente') || Gate::allows('Ospite')) {
                $categoria = Categoria::all();
                $risorsa = null;
                if (request("tipo") != null && request("tipo") == "completo") {
                    $risorsa = new CategoriaCompletoCollection($categoria);
                } else {
                    $risorsa = new CategoriaCollection($categoria);
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
     * @param \Illuminate\Http\Requests\v1\CategoriaStoreRequest $request
     * @return JsonResource
     */

    public function store(CategoriaStoreRequest $request)
    {
        if (Gate::allows('creare')) {
            if (Gate::allows('Amministratore')) {
                $dati = $request->validated();
                $categoria = Categoria::create($dati);
                return new CategoriaResource($categoria);
            } else {
                abort(403, "PE_0001");
            }
        } else {
            abort(403, "PE_0002");
        }
    }


    /**
     * Display the specified resource.
     * @param \App\Models\Categoria $categoria
     * @return JsonResource
     */


    public function show(Categoria $categoria)
    {
        if (Gate::allows('leggere')) {
            if (Gate::allows('Amministratore') || Gate::allows('Utente')) {
                $risorsa = null;
                if (request("tipo") != null && request("tipo") == "completo") {
                    $risorsa = new CategoriaCompletoResource($categoria);
                } else {
                    $risorsa = new CategoriaResource($categoria);
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


    public function update(CategoriaUpdateRequest $request, Categoria $categoria)
    {

        if (Gate::allows('aggiornare')) {
            if (Gate::allows('Amministratore')) {
                $dati = $request->validated();
                $categoria->fill($dati);
                $categoria->save();
                return new CategoriaResource($categoria);
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


    public function destroy(Categoria $categoria)
    {
        if (Gate::allows('eliminare')) {
            if (Gate::allows('Amministratore')) {
                $categoria->deleteOrFail();
                return response()->noContent();
            } else {
                abort(403, 'PE_0001');
            }
        } else {
            abort(403, 'PE_0002');
        }
    }
}
