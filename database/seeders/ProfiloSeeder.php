<?php

namespace Database\Seeders;

use App\Models\Profilo;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProfiloSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Profilo::create(
            [
                "idProfilo" => 1,
                "idContatto" => 1,
                "nome" => "Simone"
            ]
        );
        Profilo::create(
            [
                "idProfilo" => 2,
                "idContatto" => 1,
                "nome" => "Mario"
            ]
        );
        Profilo::create(
            [
                "idProfilo" => 3,
                "idContatto" => 1,
                "nome" => "Luigi"
            ]
        );
        Profilo::create(
            [
                "idProfilo" => 4,
                "idContatto" => 1,
                "nome" => "Andrea"
            ]
        );
    }
}
