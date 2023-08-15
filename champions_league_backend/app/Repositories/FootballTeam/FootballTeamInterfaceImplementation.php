<?php


namespace App\Repositories\FootballTeam;


use App\Models\FootballTeam;
use Illuminate\Support\Collection;

/**
 * Class FootballTeamInterfaceImplementation
 * @package App\Repositories\FootballTeam
 */
class FootballTeamInterfaceImplementation implements FootballTeamRepository
{

    /**
     * @param array $columns
     * @param array $with
     * @return Collection
     */
    public function getAll(array $columns, array $with): Collection
    {
        $teams = FootballTeam::with($with);

        if (count($columns) > 0){
            $teams->select($columns);
        }

        return $teams->get();
    }

    /**
     * @param int $id
     */
    public function delete(int $id): void
    {
        FootballTeam::destroy($id);
    }

    /**
     * @param array $attributes
     */
    public function store(array $attributes): void
    {
        FootballTeam::create($attributes);
    }
}
