<?php

namespace App\Http\Controllers;

use App\Services\ChampionService;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;

/**
 * Class ChampionController
 * @package App\Http\Controllers
 */
class ChampionController extends ApiController
{
    /**
     * @var ChampionService
     */
    private ChampionService $championService;

    /**
     * ChampionController constructor.
     * @param ChampionService $championService
     */
    public function __construct(ChampionService $championService)
    {
        $this->championService = $championService;
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function generate(): JsonResponse{
        $champion = $this->championService->generate();
        return $this->successResponse($champion);
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     * @throws \HttpResponseException
     */
    public function playWeek(int $id): JsonResponse{
        $WeekType = request()->query('type');

        return $this->successResponse($this->championService->playWeek($id, $WeekType));
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function stop(int $id): JsonResponse{
        $this->championService->stop($id);
        return $this->successResponse(null);
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getNextWeekFixtures(int $id): JsonResponse{
        return $this->successResponse($this->championService->getNextWeekFixtures($id, ['homeTeam:name,id', 'awayTeam:name,id']));
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getTeamResults(int $id): JsonResponse{
        return $this->successResponse($this->championService->getTeamResults($id));
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getPredictionsResults(int $id): JsonResponse{
        return $this->successResponse($this->championService->getPredictionsResults($id));
    }

}
