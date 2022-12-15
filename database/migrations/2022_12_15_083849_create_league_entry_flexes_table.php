<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('flex_league_entries', function (Blueprint $table) {
            $table->string('leagueId');
            $table->string('summonerId');
            $table->string('tier');
            $table->string('rank');
            $table->integer('lp');
            $table->integer('wins');
            $table->integer('loses');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('league_entry_flexes');
    }
};
