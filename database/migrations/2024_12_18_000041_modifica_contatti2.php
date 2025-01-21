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
        Schema::table('contatti', function (Blueprint $table) {
            // $table->dropColumn('cittadinanza');
            // $table->dropColumn('nazioneNascita');
            // $table->dropColumn('regioneNascita');
            // $table->dropColumn('CittaNascita');
            // $table->dropColumn('ProvNascita');
            // $table->dropColumn('capInizio');

            $table->dropForeign(['nazioneNascita']);
            $table->dropColumn('nazioneNascita');
            $table->dropForeign(['regioneNascita']);
            $table->dropColumn('regioneNascita');
            $table->dropForeign(['ProvNascita']);
            $table->dropColumn('ProvNascita');
            $table->dropForeign(['capInizio']);
            $table->dropColumn('capInizio');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
