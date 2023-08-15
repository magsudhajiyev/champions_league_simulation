<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fixture extends Model
{
    use HasFactory;

    protected $fillable = ['champion_id', 'round_no', 'match_no', 'home_team_id', 'away_team_id', 'home_team_goals', 'away_team_goals'];

    public function homeTeam()
    {
        return $this->belongsTo(FootballTeam::class, 'home_team_id');
    }

    public function awayTeam()
    {
        return $this->belongsTo(FootballTeam::class, 'away_team_id');
    }
}
