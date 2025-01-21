<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SerieTvContatti extends Model
{
    use HasFactory;
    protected $table = "serieTv_contatti";
    protected $primaryKey = "idSerieContatto";
    protected $fillable = [
        "idSerieTv",
        "idContatto"
    ];
}
