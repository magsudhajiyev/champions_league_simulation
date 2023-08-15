<?php


namespace App\Repositories\Fixture;


use App\Models\Fixture;
use Illuminate\Support\Collection;

/**
 * Interface FixtureRepository
 * @package App\Repositories\Fixture
 */
interface FixtureRepository
{

    /**
     * @param int $championId
     * @param int $weekRound
     * @param array $with
     * @return Collection
     */
    public function findByChampionIdAndWeekRound(int $championId, int $weekRound, $with = []): Collection;

    /**
     * @param int $championId
     * @param int $weekRound
     * @return Collection
     */
    public function findByChampionIdAndWeekRoundGreaterThan(int $championId, int $weekRound): Collection;

    /**
     * @param Fixture $fixture
     */
    public function store(Fixture $fixture): void;
}
