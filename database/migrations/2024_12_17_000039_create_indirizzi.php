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
        Schema::create('indirizzi', function (Blueprint $table) {
            $table->id("idIndirizzo");
            $table->unsignedBigInteger('idTipologiaIndirizzo');
            $table->unsignedBigInteger('idContatto');
            $table->char('nazione', 45);
            $table->char('cittadinanza', 45);
            $table->char('provincia', 45);
            $table->char('citta', 45);
            $table->unsignedBigInteger('cap');
            $table->char('indirizzo', 45);
            $table->unsignedBigInteger('civico');
            $table->char('altro', 45)->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->foreign("idContatto")->references("idContatto")->on("contatti");
            $table->foreign('idTipologiaIndirizzo')->references('idTipologiaIndirizzo')->on('tipologiaIndirizzi');
            $table->foreign('nazione')->references('nome')->on('nazioni');
            $table->foreign('cittadinanza')->references('nome')->on('cittadinanze');
            $table->foreign('provincia')->references('provincia')->on('comuni');
            $table->foreign('citta')->references('comune')->on('comuni');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('indirizzi');
    }
};
