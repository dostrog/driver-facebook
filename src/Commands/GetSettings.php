<?php

namespace BotMan\Drivers\Facebook\Commands;

use BotMan\BotMan\Http\Curl;
use Illuminate\Console\Command;

class GetSettings extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'botman:facebook:GetSettings';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get a Page level settings (menu)';

    /**
     * @var Curl
     */
    private $http;

    /**
     * Create a new command instance.
     *
     * @param Curl $http
     */
    public function __construct(Curl $http)
    {
        parent::__construct();
        $this->http = $http;
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $this->info("Using access_token: " . substr(config('botman.facebook.token'), 0, 10) . "...");

        $response = $this->http->get(
            'https://graph.facebook.com/v7.0/me/messenger_profile?fields=get_started,ice_breakers,persistent_menu,whitelisted_domains,greeting&access_token='.config('botman.facebook.token')
        );

        $responseObject = json_decode($response->getContent());

        if ($response->getStatusCode() == 200) {
            $this->info("Current values: " .
                json_encode(
                    $responseObject->data[0],
                    JSON_UNESCAPED_SLASHES |
                    JSON_UNESCAPED_UNICODE |
                    JSON_PRETTY_PRINT |
                    JSON_PARTIAL_OUTPUT_ON_ERROR |
                    JSON_INVALID_UTF8_SUBSTITUTE
                ) . PHP_EOL
            );
        } else {
            $this->error('Something went wrong: '.$responseObject->error->message);
        }
    }
}
