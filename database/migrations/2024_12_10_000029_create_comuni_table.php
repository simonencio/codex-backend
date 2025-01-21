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
        Schema::create('comuni', function (Blueprint $table) {
            $table->id('idComune');
            $table->string('comune', 45)->index();
            $table->string('regione', 45);
            $table->string('provincia', 45)->index();
            $table->string('siglaAutomobilistica', 10);
            $table->char('Cod_Catastale');
            $table->unsignedBigInteger('capoluogo');
            $table->unsignedBigInteger('multiCap');
            $table->unsignedBigInteger('capInizio');
            $table->unsignedBigInteger('capFine');
            $table->unsignedBigInteger('capSegnaposto');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comuni');
    }
};
