<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('contatti', function (Blueprint $table) {
            $table->id("idContatto");

            $table->unsignedBigInteger('idStato');
            $table->string('nome', 45)->nullable();
            $table->string('cognome', 45);
            $table->unsignedTinyInteger('sesso')->unsigned()->nullable();
            $table->string('codiceFiscale', 45);
            $table->date("dataNascita");
            $table->char('cittadinanza', 100);
            $table->char('nazioneNascita', 100);
            $table->char('regioneNascita', 100);
            $table->char('cittaNascita', 45);
            $table->char('provNascita', 45);
            $table->unsignedBigInteger('capInizio');
            $table->string('partitaIva', 45);
            $table->softDeletes();
            $table->timestamps();
            $table->foreign("idStato")->references("idStato")->on("stati");
            $table->foreign("cittadinanza")->references("nome")->on("cittadinanze");
            $table->foreign("nazioneNascita")->references("nome")->on("nazioni");
            $table->foreign('regioneNascita')->references('regione')->on('comuni');
            $table->foreign("CittaNascita")->references("comune")->on("comuni");
            $table->foreign("ProvNascita")->references("provincia")->on("comuni");
            $table->foreign("capInizio")->references("capInizio")->on("comuni");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contatti');
    }
};
