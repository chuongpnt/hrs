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
     * Reads input data from 'storage/input/levelx_x.in', calculates final heights,
     * and writes the results to 'storage/output/levelx_x.out'.
     *
     * @return string
     */
    public function flightHeightByLevel(string $groupLevel, int $index): string
    {
        if (!in_array($groupLevel, ['level1', 'level2','level3','level4', 'level5']) || $index < 1) {
            return 'Invalid level!';
        }

        $inputPath = storage_path("input/{$groupLevel}_{$index}.in");
        $outputPath = storage_path("output/{$groupLevel}_{$index}.out");

        $flightCount = 0;
        $flights = $this->getFlights($inputPath, $flightCount);

        if ($groupLevel === 'level1') {
            $results = $this->flightService->calculateFinalHeightsLevel1($flights);
        } elseif ($groupLevel === 'level2') {
            $results = $this->flightService->calculateFinalHeightsLevel2($flights);
        } else {
            return 'Level not supported!';
        }

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
