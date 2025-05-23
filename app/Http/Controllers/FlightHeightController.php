<?php

namespace App\Http\Controllers;

use App\Services\FlightHeightService;

class FlightHeightController extends Controller
{
    public function __construct(
        protected FlightHeightService $flightService
    ) {}

    /**
     * Process flight height calculation for level 1.
     *
     * Reads input data from 'storage/input/level1.in', calculates final heights,
     * and writes the results to 'storage/output/level1.in'.
     *
     * @return string
     */
    public function flightHeightLevel1(): string
    {
        $inputPath = storage_path('input/level1_1.in');
        $outputPath = storage_path('output/level1_1.out');

        $flightCount = 0;

        $flights = $this->getFlights($inputPath, $flightCount);

        $results = $this->calculateFinalHeights($flights);

        if ($flightCount < 1 || count($results) !== $flightCount) {
            return 'Quantity of flights does not match!';
        }

        $this->saveResults($outputPath, $results);

        return 'Finished file output: ' . $outputPath;
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
