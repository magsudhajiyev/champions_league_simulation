<?php


namespace App\Services;


use App\Helper\ChampionHelper;
use App\Models\Champion;
use App\Models\Fixture;
use App\Models\FootballTeam;
use App\Projections\TeamPredictionsResult;
use App\Repositories\Champion\ChampionRepository;
use App\Repositories\Fixture\FixtureRepository;
use App\Traits\ApiResponserTrait;
use Exception;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Collection;

/**
 * Class ChampionService
 * @package App\Services
 */
class ChampionService
{
    use ApiResponserTrait;

    /**
     * @var FixtureService
     */
    private FixtureService $fixtureService;

    /**
     * @var FixtureRepository
     */
    private FixtureRepository $fixtureRepository;

    /**
     * @var ChampionRepository
     */
    private ChampionRepository $championRepository;

    /**
     * ChampionService constructor.
     * @param FixtureService $fixtureService
     * @param ChampionRepository $championRepository
     * @param FixtureRepository $fixtureRepository
     */
    public function __construct(FixtureService $fixtureService, ChampionRepository $championRepository, FixtureRepository $fixtureRepository)
    {
        $this->fixtureService = $fixtureService;
        $this->fixtureRepository = $fixtureRepository;
        $this->championRepository = $championRepository;
    }

    /**
     * @return Champion
     */
    public function generate(): Champion
    {
        $teams = FootballTeam::all()->all();

        shuffle($teams);

        $fixtures = $this->fixtureService->generate($teams);

        return $this->championRepository->storeWithFixtures(['total_round'=> ChampionHelper::getTotalRoundCount($teams)], $fixtures);;
    }


    /**
     * @param int $championId
     * @param string $WeekType
     * @throws Exception
     */
    public function playWeek(int $championId, string $WeekType): Champion{
        $champion = $this->championRepository->findById($championId);

        if ($champion == null){
            throw new HttpResponseException($this->errorResponse(404, "Champion not found"));
        }

        if (ChampionHelper::isChampionFinished($champion)){
            throw new HttpResponseException($this->errorResponse(422, "Champion is finished"));
        }

        $fixtures = [];

        switch ($WeekType){
            case "NEXT_WEEK";
                $fixtures = $this->fixtureRepository->findByChampionIdAndWeekRound($championId, $champion->current_round +1);
                ++$champion->current_round;
            break;

            case "ALL_WEEKS";
                $fixtures = $this->fixtureRepository->findByChampionIdAndWeekRoundGreaterThan($championId, $champion->current_round);
                $champion->current_round = $champion->total_round;
                break;
        }

        foreach ($fixtures as $fixture){
            $this->fixtureService->playFixture($fixture);
            $this->fixtureRepository->store($fixture);
        }

        if ($champion->current_round === $champion->total_round){
            $champion->status = 'DONE';
        }elseif ($champion->current_round == 1){
            $champion->status = 'RUNNING';
        }

        $this->championRepository->storeChampion($champion);

        return $champion;
    }

    /**
     * @param int $championId
     * @return Champion
     */
    public function stop(int $championId): Champion
    {
        $champion = $this->championRepository->findById($championId);

        if ($champion == null){
            throw new HttpResponseException($this->errorResponse(404, "Champion not found"));
        }

        $champion->status = 'STOPPED';
        $champion->save();
        return $champion;
    }

    /**
     * @param int $championId
     * @param array $with
     * @return Collection
     */
    public function getNextWeekFixtures(int $championId, $with = []): Collection
    {
        $champion = $this->championRepository->findById($championId);

        if ($champion == null){
            throw new HttpResponseException($this->errorResponse(404, "Champion not found"));
        }

        return $this->fixtureRepository->findByChampionIdAndWeekRound($championId, $champion->current_round +1, $with);
    }

    /**
     * @param int $championId
     * @return array
     */
    public function getTeamResults(int $championId): Collection
    {
        $champion = $this->championRepository->findById($championId);

        if ($champion == null){
            throw new HttpResponseException($this->errorResponse(404, "Champion not found"));
        }

        return $this->championRepository->findTeamResults($championId);
    }

    /**
     * @param int $championId
     * @return array
     */
    public function getPredictionsResults(int $championId): array
    {
        $champion = $this->championRepository->findById($championId);

        if ($champion == null){
            throw new HttpResponseException($this->errorResponse(404, "Champion not found"));
        }

        $teams = $this->championRepository->getPredictionsResults($championId);
        $predictions = [];
        $maxGoals = $teams->max('goals_for');

        foreach ($teams as $team){
            $team->prediction_goals = rand(0, floor($team->strength_points/10) )* $team->remaining_match;
            $totalTeamGoals = $team->goals_for + $team->prediction_goals;
            if ($totalTeamGoals < $maxGoals){
                $team->prediction_goals = 0;
                $team->goals_for = 0;
            }
        }

        $totalGoals = $teams->sum('goals_for') + $teams->sum('prediction_goals');

        foreach ($teams as $team){
            $prediction =  ( ($team->goals_for + $team->prediction_goals)/$totalGoals)*100;
            $totalPredictions = round($prediction);
            $predictions[] = new TeamPredictionsResult($team->team_name,
                $totalPredictions < 100 ? round($prediction) : floor($prediction)  );
        }

        return $predictions;
    }
}
