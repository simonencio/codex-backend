<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Indirizzo extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = "indirizzi";
    protected $primaryKey = "idIndirizzo";
    protected $fillable = [
        "idTipologiaIndirizzo",
        "idContatto",
        "nazione",
        "cittadinanza",
        "provincia",
        "citta",
        "cap",
        "indirizzo",
        "civico",
        "altro",
    ];
    /**
     * funzione per ritorno di appartenenza
     */
    public function tipologiaIndirizzo()
    {
        return $this->belongsTo(tipologiaIndirizzo::class, 'idTipologiaIndirizzo', 'idTipologiaIndirizzo');
    }
}
