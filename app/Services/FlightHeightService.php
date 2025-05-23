<?php

namespace App\Services;

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
        $lines = file($filepath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        $flightCount = isset($lines[0]) ? (int)trim($lines[0]) : 0;
        $flights = [];
        for ($i = 1; $i < count($lines); $i++) {
            $flights[] = array_map('intval', preg_split('/\s+/', trim($lines[$i])));
        }
        return $flights;
    }

    /**
     * Calculates the final heights for an array of flights.
     *
     * For each flight (array of velocities), computes the cumulative height,
     * resetting to zero if height ever goes negative.
     *
     * @param array $flights   Array of flights, each as an array of velocities.
     * @return array           Final heights for each flight.
     */
    public function calculateFinalHeights(array $flights): array
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
        file_put_contents($filepath, $output);
    }
}
