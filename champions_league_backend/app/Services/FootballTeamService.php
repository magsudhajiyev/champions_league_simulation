<?php


namespace App\Services;


use App\Repositories\FootballTeam\FootballTeamRepository;
use Illuminate\Support\Collection;

/**
 * Class FootballTeamService
 * @package App\Services
 */
class FootballTeamService
{
    /**
     * @var FootballTeamRepository
     */
    private FootballTeamRepository $footballTeamRepository;

    /**
     * FootballTeamService constructor.
     * @param FootballTeamRepository $footballTeamRepository
     */
    public function __construct(FootballTeamRepository $footballTeamRepository)
    {
        $this->footballTeamRepository = $footballTeamRepository;
    }


    /**
     * @param array $columns
     * @param array $with
     * @return Collection
     */
    public function getAll(array $columns, array $with = []): Collection{
        return $this->footballTeamRepository->getAll($columns, $with);
    }

    /**
     * @param int $id
     */
    public function delete(int $id): void
    {
        $this->footballTeamRepository->delete($id);
    }

    /**
     * @param array $attributes
     */
    public function store(array $attributes): void
    {
        $this->footballTeamRepository->store($attributes);
    }
}
