<?php

namespace App\Http\Controllers;

use App\Http\Requests\FootballTeamStoreRequest;
use App\Services\FootballTeamService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Class FootballTeamController
 * @package App\Http\Controllers
 */
class FootballTeamController extends ApiController
{
    /**
     * @var FootballTeamService
     */
    private FootballTeamService $footballTeamService;

    /**
     * FootballTeamController constructor.
     * @param FootballTeamService $footballTeamService
     */
    public function __construct(FootballTeamService $footballTeamService)
    {
        $this->footballTeamService = $footballTeamService;
    }


    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAll(): JsonResponse
    {
        $teams = $this->footballTeamService->getAll(["name", "strength_points", "id"]);

        return $this->successResponse($teams);
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete(int $id): JsonResponse
    {
        $this->footballTeamService->delete($id);

        return $this->successResponse(null);
    }

    /**
     * @param FootballTeamStoreRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(FootballTeamStoreRequest $request): JsonResponse
    {
        $this->footballTeamService->store($request->all());

        return $this->successResponse(null);
    }
}
