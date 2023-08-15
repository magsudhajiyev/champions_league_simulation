<?php

use App\Models\Champion;
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
        Schema::create('fixtures', function (Blueprint $table) {
            $table->id();
            $table->unsignedTinyInteger("round_no");
            $table->unsignedTinyInteger("match_no");

            $table->unsignedBigInteger('home_team_id');
            $table->foreign('home_team_id')->references('id')->on('football_teams')->onDelete('cascade');;

            $table->unsignedBigInteger('away_team_id');
            $table->foreign('away_team_id')->references('id')->on('football_teams')->onDelete('cascade');;

            $table->unsignedTinyInteger('home_team_goals')->default(0);
            $table->unsignedTinyInteger('away_team_goals')->default(0);

            $table->foreignIdFor(Champion::class);

            $table->string('status')->default('PLANNED');

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
        Schema::dropIfExists('fixtures');
    }
};
