<?php

namespace Tests\Feature;

use Tests\TestCase;

class GeneralFeautureTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_get_initial_team_count_after_migration()
    {
        $response = $this->get('/api/v1/football-teams');

        $response->assertStatus(200);
        $this->assertCount(4, $response["data"]);
    }
}
