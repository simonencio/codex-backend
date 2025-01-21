<?php

namespace App\Http\Resources\v1\Resource;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class IndirizzoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request)
    {
        // return parent::toArray($request);
        return $this->getCampi();
    }


    //---PROTECTED--------------------------------------- 
    //---------------------------------------------------
    protected function getCampi()
    {
        return [
            'idIndirizzo' => $this->idIndirizzo,
            'idTipologiaIndirizzo' => $this->idTipologiaIndirizzo,
            'idContatto' => $this->idContatto,
            'nazione' => $this->nazione,
            'cittadinanza' => $this->cittadinanza,
            'provincia' => $this->provincia,
            'citta' => $this->citta,
            'cap' => $this->cap,
            'indirizzo' => $this->indirizzo,
            'civico' => $this->civico,
            'altro' => $this->altro,

        ];
    }
}
