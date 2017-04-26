<?php

namespace App\Console\Commands\Cachet;

use GuzzleHttp\Client;
use Illuminate\Console\Command;

class Working extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cachet:working';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update component status to working';

    /**
     * @var Client
     */
    protected $client;

    /**
     * Create a new command instance.
     *
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        parent::__construct();

        $this->client = $client;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        if (is_null(config('infoexam.cachet_component_id')) || is_null(config('infoexam.cachet_api_key'))) {
            return;
        }

        $url = sprintf('https://status.ccu.edu.tw/api/v1/components/%d', config('infoexam.cachet_component_id'));

        $this->client->put($url, [
            'headers' => [
                'X-Cachet-Token' => config('infoexam.cachet_api_key'),
            ],
            'json' => [
                'status' => 1,
            ],
        ]);
    }
}
