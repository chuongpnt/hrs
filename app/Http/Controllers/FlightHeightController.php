<?php

namespace App\Http\Controllers;

use App\Services\FlightHeightService;

class FlightHeightController extends Controller
{
    public function __construct(
        protected FlightHeightService $flightService
    ) {}

    /**
     * Process flight height calculation for all level.
     *
     * Reads input data from 'storage/input/level1_x.in', calculates final heights,
     * and writes the results to 'storage/output/level1_x.out'.
     *
     * @return string
     */
    public function flightHeightByLevel(int $level): string
    {
        if ($level < 1 || $level > 5) {
            return 'Invalid level!';
        }

        $inputPath = storage_path("input/level1_{$level}.in");
        $outputPath = storage_path("output/level1_{$level}.out");

        $flightCount = 0;
        $flights = $this->getFlights($inputPath, $flightCount);
        $results = $this->calculateFinalHeights($flights);

        if ($flightCount < 1 || count($results) !== $flightCount) {
            return 'Quantity of flights does not match!';
        }

        $this->saveResults($outputPath, $results);

        return "Finished file output: {$outputPath}";
    }

    /**
     * Reads flights data from the input file.
     *
     * @param string $inputPath
     * @param int &$flightCount
     * @return array
     */
    protected function getFlights(string $inputPath, int &$flightCount): array
    {
        return $this->flightService->readFlightsFromFile($inputPath, $flightCount);
    }

    /**
     * Calculates final heights for each flight.
     *
     * @param array $flights
     * @return array
     */
    protected function calculateFinalHeights(array $flights): array
    {
        return $this->flightService->calculateFinalHeights($flights);
    }

    /**
     * Writes the calculation results to the output file.
     *
     * @param string $outputPath
     * @param array $results
     * @return void
     */
    protected function saveResults(string $outputPath, array $results): void
    {
        $this->flightService->writeResultsToFile($outputPath, $results);
    }
}
