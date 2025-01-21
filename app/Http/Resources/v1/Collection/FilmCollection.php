<?php

namespace App\Http\Resources\v1\Collection;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Facades\Storage;

class FilmCollection extends ResourceCollection
{
    public function toArray(Request $request)
    {
        $tmp = parent::toArray($request);
        $tmp = array_map(array($this, 'getCampi'), $tmp);
        return $tmp;
    }

    protected function getCampi($item)
    {

        return [
            'idFilm' => $item["idFilm"],
            'idCategoria' => $item["idCategoria"],
            'titolo' => $item["titolo"],
            'descrizione' => $item["descrizione"],
            'durata' => $item["durata"],
            'regista' => $item["regista"],
            'attori' => $item["attori"],
            'anno' => $item["anno"],
        ];
    }
}
