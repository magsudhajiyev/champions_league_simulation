<?php

namespace App\Providers;

use App\Repositories\Champion\ChampionRepository;
use App\Repositories\Champion\ChampionRepositoryImplementation;
use App\Repositories\Fixture\FixtureRepository;
use App\Repositories\Fixture\FixtureRepositoryImplementation;
use App\Repositories\FootballTeam\FootballTeamInterfaceImplementation;
use App\Repositories\FootballTeam\FootballTeamRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(ChampionRepository::class, ChampionRepositoryImplementation::class);
        $this->app->bind(FixtureRepository::class, FixtureRepositoryImplementation::class);
        $this->app->bind(FootballTeamRepository::class, FootballTeamInterfaceImplementation::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
