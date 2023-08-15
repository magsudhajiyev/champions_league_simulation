<?php


namespace App\Repositories\Fixture;


use App\Models\Fixture;
use Illuminate\Support\Collection;

/**
 * Class FixtureRepositoryImplementation
 * @package App\Repositories\Fixture
 */
class FixtureRepositoryImplementation implements FixtureRepository
{

    /**
     * @param int $championId
     * @param int $weekRound
     * @param array $with
     * @return Collection
     */
    public function findByChampionIdAndWeekRound(int $championId, int $weekRound, $with= []): Collection
    {
        $fixtures = Fixture::with($with);
        return $fixtures->where('champion_id', $championId)->where('round_no', $weekRound)->get();
    }

    /**
     * @param int $championId
     * @param int $weekRound
     * @return Collection
     */
    public function findByChampionIdAndWeekRoundGreaterThan(int $championId, int $weekRound): Collection
    {
        return Fixture::where('champion_id', $championId)->where('round_no', '>', $weekRound)->get();
    }

    /**
     * @param Fixture $fixture
     */
    public function store(Fixture $fixture): void
    {
        $fixture->save();
    }
}
