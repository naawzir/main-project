<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use App\ScheduledEvent;

class CountBankHolidaysCurrentMonth extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'countBankHolidays:currentMonth';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'The number of bank holidays in the current month';

    /**
     * @var GuzzleHttp\ClientInterface
     */
    private $httpClient;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(\GuzzleHttp\ClientInterface $httpClient)
    {
        parent::__construct();
        $this->httpClient = $httpClient;
    }

    /**
     * Execute the console command.
     *
     * @return mixed|void
     */
    public function handle()
    {
        $bankHolidays = [];

        $fullResponse = $this->httpClient->request(
            'GET',
            config('app.bankHolidaysUrl'),
            [
                'headers' => [
                    'Cache-Control'=>'no-cache'
                ]
            ]
        );

        $response = $fullResponse->getBody()->getContents();

        // Decode the data into a usable format.
        $downloadedDays = json_decode($response);

        $downloadedDays = $downloadedDays->events;

        $lowerBound = strtotime('first day of this month');

        $upperBound = strtotime('last day of this month');

        // Loop through the downloaded data, and store any future values into the blank array created above.
        $count = 0;
        foreach ($downloadedDays as $days) {
            $bankHolidayTime = strtotime($days->date);

            if ($bankHolidayTime > $lowerBound && $bankHolidayTime < $upperBound) {
                //$title = str_replace('bank holiday', '', $days->title);

                //$bankHolidays[$bankHolidayTime] = trim($title);

                $count++;
            }
        }

        $bankHolidays = $count;

        $expiresAt = now()->addHours(25);

        Cache::add('countBankHolidaysInCurrentMonth', $bankHolidays, $expiresAt);

        $scheduledEvent = new ScheduledEvent;
        $scheduledEvent->log(
            'countBankHolidays:currentMonth',
            'Number of bank holidays in current month ' . '(' . $count . ')'
        );

        $this->info($count);
    }
}
