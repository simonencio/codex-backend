<?php

namespace Database\Seeders;

use App\Models\Comune;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ComuneSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $csv = storage_path("app/csv_db/comuniItaliani.csv");
        $file = fopen($csv, "r");
        while (($data = fgetcsv($file, 200, ",")) !== false) {
            Comune::create(
                [
                    "idComune" => $data[0],
                    "comune" => $data[1],
                    "regione" => $data[2],
                    "provincia" => $data[3],
                    "siglaAutomobilistica" => $data[4],
                    "Cod_Catastale" => $data[5],
                    "capoluogo" => $data[6],
                    "multiCap" => $data[7],
                    "capInizio" => $data[8],
                    "capFine" => $data[9],
                    "capSegnaposto" => $data[10]
                ]
            );
        }
    }
}
