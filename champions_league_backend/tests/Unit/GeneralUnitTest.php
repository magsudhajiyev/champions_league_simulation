<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\FixtureService;
use App\Models\Champion;
use App\Repositories\Champion\ChampionRepository;
use App\Repositories\Fixture\FixtureRepository;
use App\Services\ChampionService;
use Illuminate\Database\Eloquent\Collection;

class GeneralUnitTest extends TestCase
{
    public function test_getTeamResults_returns_team_results()
    {
        // Create mock instances for dependencies
        $fixtureServiceMock = $this->createMock(FixtureService::class);
        $championRepositoryMock = $this->createMock(ChampionRepository::class);
        $fixtureRepositoryMock = $this->createMock(FixtureRepository::class);

        $championService = new ChampionService($fixtureServiceMock, $championRepositoryMock, $fixtureRepositoryMock);

        $championId = 1;

        $championMock = $this->createMock(Champion::class);

        $championRepositoryMock->expects($this->once())
            ->method('findById')
            ->with($championId)
            ->willReturn($championMock);

        $expectedResults = new Collection(
            ['name' => 'Liverpool', 'strength_points' => 70 ],
            ['name' => 'Manchester City', 'strength_points' => 75 ],
            ['name' => 'Chelsea', 'strength_points' => 60 ],
            ['name' => 'Arsenal', 'strength_points' => 80 ]
        );
        
        $championRepositoryMock->expects($this->once())
            ->method('findTeamResults')
            ->with($championId)
            ->willReturn($expectedResults);

        $result = $championService->getTeamResults($championId);

        $this->assertInstanceOf(Collection::class, $result);
        $this->assertEquals($expectedResults, $result);
    }

    
}
