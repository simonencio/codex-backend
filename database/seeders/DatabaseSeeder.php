<?php

namespace Database\Seeders;

use App\Models\contattiRuoli_contattiAbilita;
use App\Models\SerieTvContatti;
use App\Models\TipoRecapito;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(
            [
                // NazioneSeeder::class,
                // ComuneSeeder::class,
                // TipologiaIndirizzoSeeder::class,
                // StatoSeeder::class,
                // CittadinanzaSeeder::class,
                // IndirizzoSeeder::class,
                // TipoRecapitoSeeder::class,
                // RecapitoSeeder::class,
                // ProfiloSeeder::class,
                // CreditoSeeder::class,
                // CategoriaSeeder::class,
                // SerieTvSeeder::class,
                EpisodioSeeder::class,
                // FilmSeeder::class,
                // FilmContattiSeeder::class,
                // LinguaSeeder::class,
                // ConfigurazioneSeeder::class,
                // ContattoRuoloSeeder::class,
                // ContattoAbilitaSeeder::class,
                // ContattiRuoliContattiAbilitaSeeder::class,
                // SerieTvContattiSeeder::class
            ]
        );
    }
}
