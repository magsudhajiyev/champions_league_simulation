<?php


namespace App\Repositories\FootballTeam;

use App\Models\FootballTeam;
use Illuminate\Support\Collection;

/**
 * Interface FootballTeamRepository
 * @package App\Repositories\FootballTeam
 */
interface FootballTeamRepository
{
    /**
     * @param array $columns
     * @param array $with
     * @return Collection
     */
    public function getAll(array $columns, array $with): Collection;

    /**
     * @param int $id
     */
    public function delete(int $id): void;

    /**
     * @param array $attributes
     */
    public function store(array $attributes): void;
}
