<?php


namespace App\Helper;


class ChampionHelper
{
    public static function getRoundCount($teams){
        return count($teams) - 1;
    }

    public static function getTotalRoundCount($teams){
        return Self::getRoundCount($teams)*2;
    }

    public static function isChampionFinished($champion){
        return in_array($champion->status, ['DONE', 'STOPPED']);
    }
}
