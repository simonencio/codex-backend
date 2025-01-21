<?php

namespace App\Http\Resources\v1\Collection;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ContattoCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request)
    {
        $tmp = parent::toArray($request);
        $tmp = array_map(array($this, 'getCampi'), $tmp);
        return $tmp;
    }

    protected function getCampi($item)
    {
        return [
            'idContatto' => $item["idContatto"],
            'nome' => $item["nome"],
            'cognome' => $item["cognome"]

        ];
    }
}
