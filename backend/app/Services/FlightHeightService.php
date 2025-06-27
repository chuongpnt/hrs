<?php

namespace App\Services;

use App\Helpers\GraylogHelper;

class FlightHeightService
{
    /**
     * Reads flight data from the specified input file.
     *
     * The first line of the file must indicate the number of flights.
     * Each subsequent line represents a flight with velocities separated by spaces.
     *
     * @param string $filepath   Absolute path to the input file.
     * @param int|null &$flightCount  Output parameter for the number of flights.
     * @return array             Array of flights, each as an array of integers.
     */
    public function readFlightsFromFile(string $filepath, ?int &$flightCount = null): array
    {
        if (!file_exists($filepath)) {
            GraylogHelper::error('Input file not found', [
                'filepath' => $filepath
            ]);
            $flightCount = 0;
            return [];
        }

        $lines = file($filepath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        $flightCount = isset($lines[0]) ? (int)trim($lines[0]) : 0;
        $flights = [];
        for ($i = 1; $i < count($lines); $i++) {
            $flights[] = array_map('intval', preg_split('/\s+/', trim($lines[$i])));
        }
        return $flights;
    }

    /**
     * Calculates the final heights for each flight at level 1.
     *
     * Level 1: Each flight is a sequence of velocities.
     * - Drone starts at height 0
     * - For each tick:
     *     height = height (prev) + velocity
     *     if height < 0, set height = 0 (the drone never goes below ground)
     *
     * @param array $flights  Each flight is an array of velocities (positive or negative integers)
     * @return array          Final height of each flight
     */
    public function calculateFinalHeightsLevel1(array $flights): array
    {
        $results = [];
        foreach ($flights as $velocities) {
            $height = 0;
            foreach ($velocities as $v) {
                $height += $v;
                if ($height < 0) {
                    $height = 0;
                }
            }
            $results[] = $height;
        }
        return $results;
    }

    /**
     * Calculates the final heights for each flight at level 2.
     * Level 2: Each flight is a sequence of accelerations.
     * - Drone starts at height 0, velocity 0
     * - Gravity = 10
     * - Each tick:
     *     velocity = velocity (prev) + acceleration - 10
     *     height = height (prev) + velocity
     *     if height < 0, set height = 0
     *
     * @param array $flights  Each flight is an array of accelerations (positive integers)
     * @return array          Final height of each flight
     */
    public function calculateFinalHeightsLevel2(array $flights): array
    {
        $GRAVITY = 10;
        $results = [];

        foreach ($flights as $accelerations) {
            $velocity = 0;
            $height = 0;
            foreach ($accelerations as $a) {
                $velocity += $a;
                $velocity -= $GRAVITY;
                $height += $velocity;
                if ($height < 0) {
                    $height = 0;
                }
            }
            $results[] = $height;
        }

        return $results;
    }


    /**
     * Writes calculation results to the specified output file.
     *
     * Each result is written on a separate line, matching ACM/OLP contest output format.
     *
     * @param string $filepath   Absolute path to the output file.
     * @param array $results     Array of results to write.
     * @return void
     */
    public function writeResultsToFile(string $filepath, array $results): void
    {
        $output = implode(PHP_EOL, $results);
        $written = file_put_contents($filepath, $output);

        if ($written === false) {
            GraylogHelper::error('Failed to write results', [
                'filepath' => $filepath,
                'results' => $results,
            ]);
        } else {
            GraylogHelper::info('Results written to file', [
                'filepath' => $filepath,
                'resultsCount' => count($results),
            ]);
        }
    }
}
