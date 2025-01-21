<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\v1\Store\FilmStoreRequest;
use App\Http\Requests\v1\Update\FilmUpdateRequest;
use App\Http\Resources\v1\Collection\FilmCollection;
use App\Http\Resources\v1\CollectionComplete\FilmCompletoCollection;
use App\Http\Resources\v1\Resource\FilmResource;
use App\Http\Resources\v1\ResourceComplete\FilmCompletoResource;
use App\Models\Film;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

class FilmController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function index()
    {
        if (Gate::allows('leggere')) {
            if (Gate::allows('Amministratore') || Gate::allows('Utente') || Gate::allows('Ospite')) {
                $film = Film::all();
                $risorsa = null;



                if (request("tipo") != null && request("tipo") == "completo") {
                    $risorsa = new FilmCompletoCollection($film);
                } else {
                    $risorsa = new FilmCollection($film);
                }

                return $risorsa;
            } else {
                abort(403, 'PE_0001');
            }
        } else {
            abort(403, 'PE_0002');
        }
    }


    // Function to format the ID
    protected function formatId($id)
    {
        if ($id < 10) {
            return '0000' . $id;
        } elseif ($id < 100) {
            return '000' . $id;
        } elseif ($id < 1000) {
            return '00' . $id;
        } elseif ($id < 10000) {
            return '0' . $id;
        } else {
            return (string)$id;
        }
    }

    /**
     * Store a newly created resource in storage.
     *  * @param \Illuminate\Http\Requests\v1\IndirizzoStoreRequest $request
     * @return JsonResponse
     */
    public function store(FilmStoreRequest $request)
    {
        if (Gate::allows('creare')) {
            if (Gate::allows('Amministratore')) {
                $dati = $request->validated();
                $film = Film::create($dati);
                return new FilmResource($film);
            } else {
                abort(403, "PE_0001");
            }
        } else {
            abort(403, "PE_0002");
        }
    }
    // modificare per l'upload di locandine e trailer
    /**
     * Display the specified resource.
     * @param \App\Models\Indirizzo
     * @return JsonResource
     */


    public function show(Film $film)
    {
        if (Gate::allows('leggere')) {
            if (Gate::allows('Amministratore') || Gate::allows('Utente')) {
                $risorsa = null;
                $folderName = "ID" . $film->idFilm;
                $versionFolder = "V1";
                $contenuti = Storage::files("film/$folderName/$versionFolder");
                $film->contenuti = $contenuti;

                if (request("tipo") != null && request("tipo") == "completo") {
                    $risorsa = new FilmCompletoResource($film);
                } else {
                    $risorsa = new FilmResource($film);
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
    public function update(FilmUpdateRequest $request, Film $film)
    {

        if (Gate::allows('aggiornare')) {
            if (Gate::allows('Amministratore')) {
                // user is an Amministratore, allow update without restrictions
                $dati = $request->validated();
                $film->fill($dati);
                $film->save();
                return new FilmResource($film);
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
    public function destroy(Film $film)
    {
        if (Gate::allows('eliminare')) {
            if (Gate::allows('Amministratore')) {
                $film->deleteOrFail();
                return response()->noContent();
            } else {
                abort(403, 'PE_0001');
            }
        } else {
            abort(403, 'PE_0002');
        }
    }
}
