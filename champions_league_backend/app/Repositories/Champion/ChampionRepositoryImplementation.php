<?php


namespace App\Repositories\Champion;


use App\Models\Champion;
use App\Models\FootballTeam;
use Illuminate\Support\Collection;

/**
 * Class ChampionRepositoryImplementation
 * @package App\Repositories\Champion
 */
class ChampionRepositoryImplementation implements ChampionRepository
{

    /**
     * @param array $attributes
     * @param array $fixtures
     * @return Champion
     */
    public function storeWithFixtures(array $attributes, array $fixtures): Champion
    {
        $champion =  Champion::create($attributes);

        $champion->fixtures()->createMany($fixtures);

        return $champion;
    }

    /**
     * @param Champion $champion
     */
    public function storeChampion(Champion $champion): void
    {
        $champion->save();
    }

    /**
     * @param int $id
     * @return Champion
     */
    public function findById(int $id): Champion
    {
        return Champion::find($id);
    }

    /**
     * @param int $id
     * @return Collection
     */
    public function findTeamResults(int $id): Collection
    {
        return FootballTeam::
        join("fixtures as f", function($join)
        {
            $join->on('football_teams.id', '=', 'f.home_team_id')->orOn('football_teams.id', 'f.away_team_id');
        })->
        select('football_teams.name as team_name')->
        selectRaw("sum( case when f.status = 'DONE' then 1 else 0 end ) play")->
        selectRaw("SUM(
                        case
                            when f.status = 'DONE' and football_teams.id = f.home_team_id AND f.home_team_goals > f.away_team_goals then 1
                            when f.status = 'DONE' and football_teams.id = f.away_team_id AND f.home_team_goals < f.away_team_goals then 1
                        ELSE 0 END
                    ) win")->
        selectRaw("SUM(
                        case
                            when f.status = 'DONE' and f.home_team_goals = f.away_team_goals then 1
                        ELSE 0 END
                    ) draw")->
        selectRaw("SUM(
                    case
                        when f.status = 'DONE' and football_teams.id = f.home_team_id AND f.home_team_goals < f.away_team_goals then 1
                        when f.status = 'DONE' and football_teams.id = f.away_team_id AND f.home_team_goals > f.away_team_goals then 1
                    ELSE 0 END
                    ) loss")->
        selectRaw("SUM(
                        case
                            when f.status = 'DONE' and football_teams.id = f.home_team_id then f.home_team_goals
                            when f.status = 'DONE' and football_teams.id = f.away_team_id then f.away_team_goals
                            else 0
                        end
                    ) goals_for")->
        selectRaw("SUM(
                    case
                        when f.status = 'DONE' and football_teams.id = f.home_team_id then f.away_team_goals
                        when f.status = 'DONE' and football_teams.id = f.away_team_id then f.home_team_goals
                        else 0
                    end
                    ) goals_against")->
        groupBy('football_teams.name')->
        where('f.champion_id', $id)->get();
    }

    /**
     * @param int $id
     * @return Collection
     */
    public function getPredictionsResults(int $id): Collection
    {
        $teams = FootballTeam::
        join("fixtures as f", function($join)
        {
            $join->on('football_teams.id', '=', 'f.home_team_id')->orOn('football_teams.id', 'f.away_team_id');
        })->
        select("football_teams.name as team_name", "football_teams.strength_points")->
        selectRaw("SUM(
                    case
                        when football_teams.id = f.home_team_id then f.home_team_goals
                        when football_teams.id = f.away_team_id then f.away_team_goals
                    end
                    ) goals_for")->
        selectRaw("SUM(
                    case when f.`status` = 'PLANNED' then 1 ELSE 0 end
                    ) remaining_match")->
        groupBy("football_teams.name", "football_teams.strength_points")->
        where('f.champion_id', $id)->get();

        return $teams;
    }
}
