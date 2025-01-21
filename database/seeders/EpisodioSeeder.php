<?php

namespace Database\Seeders;

use App\Models\Episodio;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EpisodioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Episodio::create(
            [
                "idEpisodio" => 1,
                "idSerieTv" => 1,
                "titolo" => "Indiana Jones",
                "descrizione" => "Indiana Jones",
                "numeroStagione" => 1,
                "NumeroEpisodio" => 1,
                "durata" => 20,
                "anno" => 1998
            ]
        );
        Episodio::create(
            [
                "idEpisodio" => 2,
                "idSerieTv" => 1,
                "titolo" => "Indiana Jones",
                "descrizione" => "Indiana Jones",
                "numeroStagione" => 1,
                "NumeroEpisodio" => 1,
                "durata" => 20,
                "anno" => 1998
            ]
        );
        Episodio::create(

            [
                "idEpisodio" => 3,
                "idSerieTv" => 1,
                "titolo" => "Indiana Jones",
                "descrizione" => "Indiana Jones",
                "numeroStagione" => 1,
                "NumeroEpisodio" => 1,
                "durata" => 20,
                "anno" => 1998
            ]
        );
        Episodio::create(

            [
                "idEpisodio" => 4,
                "idSerieTv" => 1,
                "titolo" => "Indiana Jones",
                "descrizione" => "Indiana Jones",
                "numeroStagione" => 1,
                "NumeroEpisodio" => 1,
                "durata" => 20,
                "anno" => 1998
            ]
        );
        Episodio::create(
            [
                "idEpisodio" => 5,
                "idSerieTv" => 1,
                "titolo" => "Indiana Jones",
                "descrizione" => "Indiana Jones",
                "numeroStagione" => 1,
                "NumeroEpisodio" => 1,
                "durata" => 20,
                "anno" => 1998
            ]
        );
        Episodio::create(
            [
                "idEpisodio" => 6,
                "idSerieTv" => 1,
                "titolo" => "Indiana Jones",
                "descrizione" => "Indiana Jones",
                "numeroStagione" => 1,
                "NumeroEpisodio" => 1,
                "durata" => 20,
                "anno" => 1998
            ]
        );
    }
}
