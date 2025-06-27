<?php

namespace App\Http\Controllers;

use App\Enums\FlightGroupLevel;
use App\Helpers\GraylogHelper;
use App\Jobs\SendBulkMailJob;
use App\Services\FlightHeightService;
use Illuminate\Support\Facades\Bus;

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
        GraylogHelper::info('flightHeightByLevel called', [
            'groupLevel' => $groupLevel,
            'index' => $index,
        ]);

        if (!in_array($groupLevel, [
                    FlightGroupLevel::Level1->value,
                    FlightGroupLevel::Level2->value,
                    FlightGroupLevel::Level3->value,
                    FlightGroupLevel::Level4->value,
                    FlightGroupLevel::Level5->value,
                ]
            ) || $index < 1) {

            GraylogHelper::warning('Invalid level/index in flightHeightByLevel', [
                'groupLevel' => $groupLevel,
                'index' => $index,
            ]);
            return 'Invalid level!';
        }

        $inputPath = storage_path("input/{$groupLevel}_{$index}.in");
        $outputPath = storage_path("output/{$groupLevel}_{$index}.out");

        $flightCount = 0;
        $flights = $this->getFlights($inputPath, $flightCount);

        if ($groupLevel ===  FlightGroupLevel::Level1->value) {
            $results = $this->flightService->calculateFinalHeightsLevel1($flights);
        } elseif ($groupLevel ===  FlightGroupLevel::Level2->value) {
            $results = $this->flightService->calculateFinalHeightsLevel2($flights);
        } else {
            GraylogHelper::warning('Level not supported in flightHeightByLevel', [
                'groupLevel' => $groupLevel,
                'index' => $index,
            ]);
            return 'Level not supported!';
        }


        if ($flightCount < 1 || count($results) !== $flightCount) {
            GraylogHelper::error('Quantity of flights does not match', [
                'flightCount' => $flightCount,
                'resultsCount' => count($results),
                'groupLevel' => $groupLevel,
                'index' => $index,
            ]);
            return 'Quantity of flights does not match!';
        }

        $this->saveResults($outputPath, $results);

        GraylogHelper::info('Finished writing output file', [
            'outputPath' => $outputPath,
            'groupLevel' => $groupLevel,
            'index' => $index,
        ]);

        // disale rabbitmq
        // $this->sendBulkEmails();

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

    public function sendBulkEmails()
    {
        $startTime = microtime(true);

        // Create 1000 emails list
        $emails = [];
        for ($i = 1; $i <= 1000; $i++) {
            $emails[] = "user{$i}@example.com";
        }

        // Configuration emails per batch
        $batchSize = 100;
        $emailBatches = array_chunk($emails, $batchSize);

        // Dispatch
        $batchCount = 0;
        foreach ($emailBatches as $batchIndex => $batch) {
            // Add all email of a batch into job
            $jobs = array_map(function ($email) use ($batchIndex) {
                return new SendBulkMailJob($email, 'This is RabbmitMQ test.', $batchIndex + 1);
            }, $batch);

            Bus::batch($jobs)
                ->onQueue('Mail') // set job name
                ->then(function () use ($startTime, &$batchCount, $batchSize) {
                $batchCount++;
            })->dispatch();
        }
        return 'Finished ' . count($emailBatches) . '!';
    }
}
