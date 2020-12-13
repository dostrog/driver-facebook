<?php
declare(strict_types=1);

namespace BotMan\Drivers\Facebook\Commands;

use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Console\Command;
use GuzzleHttp\Client;

class RemovePersistentMenu extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'botman:facebook:RemoveMenu';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove a persistent Facebook menu';

    /**
     * @var Client
     */
    private Client $http;

    /**
     * Create a new command instance.
     *
     * @param Client $http
     */
    public function __construct(Client $http)
    {
        parent::__construct();
        $this->http = $http;
    }

    /**
     * Execute the console command.
     *
     * @return void
     * @throws GuzzleException
     */
    public function handle()
    {
        $payload = ['fields' => ['persistent_menu']];

        $this->info("Bot config: " . (env('FACEBOOK_BOT_NAME') ?? '[no key FACEBOOK_BOT_NAME in env]'));
        $this->info("Using access_token: " . substr(config('botman.facebook.token'), 0, 40) . "...");
        $this->info("Using app_id: " . config('botman.facebook.app_id'));

        $response = $this->http->delete(
            config('botman.facebook.graph_url') . 'me/messenger_profile?access_token=' . config('botman.facebook.token'),
            ['json' => $payload]
        );

        if ($response->getStatusCode() === 200) {
            $this->info('Facebook menu has been deleted.');
        } else {
            $this->error('Something went wrong: ' . $response->getReasonPhrase());
        }
    }
}
