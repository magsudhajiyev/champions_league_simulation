<?php


namespace App\Projections;


use Illuminate\Queue\SerializesModels;
use JsonSerializable;

class TeamPredictionsResult implements   JsonSerializable
{
    private string $teamName;

    private float $predicationPercentage;

    /**
     * TeamPredictionsResult constructor.
     * @param string $teamName
     * @param float $predicationPercentage
     */
    public function __construct(string $teamName, float $predicationPercentage)
    {
        $this->teamName = $teamName;
        $this->predicationPercentage = $predicationPercentage;
    }

    /**
     * @return string
     */
    public function getTeamName(): string
    {
        return $this->teamName;
    }

    /**
     * @param string $teamName
     */
    public function setTeamName(string $teamName): void
    {
        $this->teamName = $teamName;
    }

    /**
     * @return float
     */
    public function getPredicationPercentage(): float
    {
        return $this->predicationPercentage;
    }

    /**
     * @param float $predicationPercentage
     */
    public function setPredicationPercentage(float $predicationPercentage): void
    {
        $this->predicationPercentage = $predicationPercentage;
    }

    public function jsonSerialize()
    {
        return  ["team_name" => $this->getTeamName(),
                 "predication_percentage" => $this->getPredicationPercentage()];
    }
}
