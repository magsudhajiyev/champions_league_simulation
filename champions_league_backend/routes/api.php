<?php

use App\Http\Controllers\ChampionController;
use App\Http\Controllers\FootballTeamController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

$app_version = env('APP_VERSION', 'v1');


Route::group(['prefix' => $app_version, 'middleware' => []], function () {

    Route::prefix('champions')->name('champions.')->controller(ChampionController::class)->group(function () {
        Route::get('generate', 'generate');
        Route::get('{id}/play-week', 'playWeek');
        Route::get('{id}/stop', 'stop');
        Route::get('{id}/fixtures/next-week', 'getNextWeekFixtures');
        Route::get('{id}/team-results', 'getTeamResults');
        Route::get('{id}/predictions-results', 'getPredictionsResults');
    });


    Route::prefix('football-teams')->name('footballTeams.')->controller(FootballTeamController::class)->group(function () {
        Route::get('', 'getAll');
        Route::delete('{id}', 'delete');
        Route::post('', 'store');
    });

});
