<?php


namespace App\Services;


use App\Helper\ChampionHelper;
use App\Models\Fixture;
use Carbon\Carbon;
use Illuminate\Support\Collection;

/**
 * Class FixtureService
 * @package App\Services
 */
class FixtureService
{
    /**
     * @param int $roundNo
     * @param Collection $teams
     * @param array $offsetArray
     * @param int $matchesPerRoundCount
     * @return array
     */
    public function getHomes(int $roundNo, array $teams, array $offsetArray, int $matchesPerRoundCount): array
    {
        $offset = count($teams) - $roundNo;
        $offsetArray = array_slice($offsetArray, $offset, ($offset + $matchesPerRoundCount - 1)- $offset );
        return array_merge([0], $offsetArray);
    }

    /**
     * @param int $roundNo
     * @param Collection $teams
     * @param array $offsetArray
     * @param int $matchesPerRoundCount
     * @return array
     */
    function getAways(int $roundNo, array $teams, array $offsetArray, int $matchesPerRoundCount): array {
        $offset = (count($teams) - $roundNo) + ($matchesPerRoundCount - 1);
        $offsetArray =array_slice($offsetArray, $offset, ($offset + $matchesPerRoundCount) - $offset);
        return array_reverse($offsetArray);
    }

    /**
     * @param Collection $teams
     * @return array
     */
    function generateOffsetArray(array $teams): array
    {
        $offsetArray = [];
        for ($i = 1; $i < count($teams); $i++) {
            $offsetArray[] = $i;
        }

        $offsetArray = array_merge($offsetArray, $offsetArray);

        return $offsetArray;
    }

    /**
     * @param Collection $teams
     * @return array
     */
    public function generate(array $teams): array{
        $roundCount = ChampionHelper::getRoundCount($teams);
        $matchesPerRoundCount = floor(count($teams) / 2);

        $firstFixtures = $this->generateRoundFixtures(0, $roundCount, true, $matchesPerRoundCount, $teams);
        $secondFixtures = $this->generateRoundFixtures(count($teams)-1, $roundCount, false, $matchesPerRoundCount, $teams);

        return array_merge($firstFixtures,$secondFixtures);
    }

    /**
     * @param int $roundNoOffset
     * @param int $roundCount
     * @param bool $alternate
     * @param int $matchesPerRoundCount
     * @param Collection $teams
     * @return array
     */
    function generateRoundFixtures(int $roundNoOffset, int $roundCount, bool $alternate, int $matchesPerRoundCount, array $teams): array
    {
        $fixtures = [];
        $offsetArray = $this->generateOffsetArray($teams);

        for ($roundNo = 1; $roundNo <= $roundCount; $roundNo++) {
            $alternate = !$alternate;

            $homes = $this->getHomes($roundNo, $teams, $offsetArray, $matchesPerRoundCount);
            $aways = $this->getAways($roundNo, $teams, $offsetArray, $matchesPerRoundCount);

            for ($matchIndex = 0; $matchIndex < $matchesPerRoundCount; $matchIndex++) {

                if ($alternate === true) {
                    $fixtures[] = [
                        'round_no' => $roundNo  + $roundNoOffset,
                        'match_no' => $matchIndex + 1,
                        'home_team_id' => $teams[$homes[$matchIndex]]->id,
                        'away_team_id' => $teams[$aways[$matchIndex]]->id,
                        'created_at' => Carbon::now(),

                    ];

                } else {
                    $fixtures[] = [
                        'round_no'=> $roundNo + $roundNoOffset,
                        'match_no'=> $matchIndex + 1,
                        'home_team_id'=> $teams[$aways[$matchIndex]]->id,
                        'away_team_id'=> $teams[$homes[$matchIndex]]->id,
                        'created_at' => Carbon::now(),
                    ];
                }
            }
        }
        return $fixtures;
    }

    /**
     * @param Fixture $fixture
     */
    public function playFixture(Fixture $fixture): void{
        $fixture->away_team_goals = rand(0, floor($fixture->awayTeam->strength_points/10) );
        $fixture->home_team_goals = rand(0, floor($fixture->homeTeam->strength_points/10) );
        $fixture->status = 'DONE';
    }
}
