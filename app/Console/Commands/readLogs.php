<?php

namespace App\Console\Commands;

use App\Logs;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\LazyCollection;


class readLogs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'read:logs';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

        LazyCollection::make(function () {
            $filename = "nasaLogs.txt";
            $handle = fopen(storage_path('app/' . $filename), "r");

            while (($line = fgets($handle)) !== false) {
                yield $line;
            }
        })->chunk(1000)->each(function ($logEntries) {
            foreach ($logEntries as $logEntry) {
                try {

                    $line = $logEntry;
                    $splitLine = explode(' - - ', $line, 2);
                    $client = $splitLine[0];
                    preg_match_all("/\\[(.*?)\\]/", $line, $timeStamp);
                    $timeStamp = $timeStamp[1][0];
                    preg_match_all('/\\"(.*?)\\"/', $line, $url);
                    $url = $url[1][0];
                    $splitLine = explode('" ', $line, 2);
                    $lastSplit = explode(' ', $splitLine[1], 2);
                    $statusCode = $lastSplit[0];
                    $resTime = $lastSplit[1];

                    $log = new Logs;
                    $log->client = $client;
                    $log->timeStamp = $timeStamp;
                    $log->url = $url;
                    $log->statusCode = $statusCode;
                    $log->resTime = $resTime;
                    $log->save();

                    $this->info($log->id);

                } catch (Exception $e) {
                    $this->error($e);
                }

            }

        });

    }
}
