<?php

namespace App\Http\Resources\v1\Resource;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ComuneResource extends JsonResource
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
            'idComune' => $this->idComune,
            'comune' => $this->comune,
            'regione' => $this->regione,
            'provincia' => $this->provincia,
            'multiCap' => $this->multiCap,
            'capInizio' => $this->capInizio,
            'capFine' => $this->capFine,
            'capSegnaposto' => $this->capSegnaposto,
            'Cod_Catastale' => $this->Cod_Catastale,
        ];
    }
}
