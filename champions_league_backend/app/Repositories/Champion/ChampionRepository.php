<?php


namespace App\Repositories\Champion;


use App\Models\Champion;
use App\Models\Fixture;
use Illuminate\Support\Collection;

/**
 * Interface ChampionRepository
 * @package App\Repositories\Champion
 */
interface ChampionRepository
{
    /**
     * @param array $attributes
     * @param array $fixtures
     * @return Champion
     */
    public function storeWithFixtures(array $attributes, array $fixtures): Champion;

    /**
     * @param Champion $champion
     */
    public function storeChampion(Champion $champion): void;

    /**
     * @param int $id
     * @return Champion
     */
    public function findById(int $id): Champion;

    /**
     * @param int $id
     * @return Collection
     */
    public function findTeamResults(int $id): Collection;

    /**
     * @param int $id
     * @return Collection
     */
    public function getPredictionsResults(int $id): Collection;

}
