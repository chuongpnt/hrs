<?php

namespace Tests\Unit;

use App\Services\FlightHeightService;
use Tests\TestCase;

class FlightHeightLevel2Test extends TestCase
{
    protected FlightHeightService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new FlightHeightService();
    }

    /** @test */
    public function test_level2_calculate_final_heights__example()
    {
        $flights = [
            [15, 7, 6, 13],
            [11, 18, 1, 14],
            [18, 7, 2, 5]
        ];
        $expected = [6, 14, 2];
        $this->assertEquals($expected, $this->service->calculateFinalHeightsLevel2($flights));
    }

    /** @test */
    public function test_level2_acceleration_equal_gravity()
    {
        $flights = [
            [10, 10, 10],
        ];
        $expected = [0];
        $this->assertEquals($expected, $this->service->calculateFinalHeightsLevel2($flights));
    }

    /** @test */
    public function test_level2_acceleration_less_than_gravity()
    {
        $flights = [
            [2, 3, 4, 5],
        ];
        $expected = [0];
        $this->assertEquals($expected, $this->service->calculateFinalHeightsLevel2($flights));
    }

    /** @test */
    public function test_level2_single_tick()
    {
        $flights = [
            [15],
            [10],
            [5]
        ];
        $expected = [5, 0, 0];
        $this->assertEquals($expected, $this->service->calculateFinalHeightsLevel2($flights));
    }

    /** @test */
    public function test_level2_multiple_resets()
    {
        $flights = [
            [5, 5, 5, 20],
        ];
        $expected = [0];
        $this->assertEquals($expected, $this->service->calculateFinalHeightsLevel2($flights));
    }

    /** @test */
    public function test_level2_long_flight_various_accelerations()
    {
        $flights = [
            [12, 9, 15, 8, 20, 3, 2, 1, 7, 12]
        ];

        $result = $this->service->calculateFinalHeightsLevel2($flights);
        $this->assertIsArray($result);
        $this->assertGreaterThanOrEqual(0, $result[0]);
    }

    public function test_level2_empty_input()
    {
        $flights = [];
        $expected = [];
        $this->assertEquals($expected, $this->service->calculateFinalHeightsLevel2($flights));
    }
}
