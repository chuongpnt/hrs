<?php

namespace Tests\Unit;

use App\Http\Controllers\FlightHeightController;
use App\Services\FlightHeightService;
use Tests\TestCase;

class FlightHeightLevel1Test extends TestCase
{
    protected FlightHeightService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new FlightHeightService();
    }

    public function test_calculate_final_heights_basic()
    {
        $flights = [
            [10, -3, -1, 6],
            [4, 3, 7, 1],
            [38, 7, -17, 5],
            [10, -20, 5]
        ];
        $expected = [12, 15, 33, 5];

        $this->assertEquals($expected, $this->service->calculateFinalHeightsLevel1($flights));
    }

    public function test_calculate_final_heights_all_negative()
    {
        $flights = [
            [-10, -5, -20],
        ];
        $expected = [0];
        $this->assertEquals($expected, $this->service->calculateFinalHeightsLevel1($flights));
    }

    public function test_calculate_final_heights_multiple_resets()
    {
        $flights = [
            [10, -5, -10, 20, -50, 30],
        ];
        $expected = [30];
        $this->assertEquals($expected, $this->service->calculateFinalHeightsLevel1($flights));
    }

    public function test_calculate_final_heights_single_element()
    {
        $flights = [
            [15],
            [-8],
            [0]
        ];
        $expected = [15, 0, 0];
        $this->assertEquals($expected, $this->service->calculateFinalHeightsLevel1($flights));
    }

    public function test_calculate_final_heights_empty_input()
    {
        $flights = [];
        $expected = [];
        $this->assertEquals($expected, $this->service->calculateFinalHeightsLevel1($flights));
    }

    public function test_flight_height_by_level_count_mismatch()
    {
        $service = $this->createMock(FlightHeightService::class);
        $controller = new FlightHeightController($service);

        $service->method('readFlightsFromFile')->willReturnCallback(function ($inputPath, &$flightCount) {
            $flightCount = 2;
            return [[1], [2]];
        });
        $service->method('calculateFinalHeightsLevel1')->willReturn([1]);

        $this->assertEquals(
            'Quantity of flights does not match!',
            $controller->flightHeightByLevel('level1',1)
        );
    }
}
