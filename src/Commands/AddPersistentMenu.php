<?php

namespace BotMan\Drivers\Facebook\Commands;

use BotMan\BotMan\Http\Curl;
use Illuminate\Console\Command;

class AddPersistentMenu extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'botman:facebook:AddMenu';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add a persistent Facebook menu';

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
        $menu_config = 'botman.facebook.persistent_menu';
        $payload = ['persistent_menu' => config($menu_config)];

        if (! $payload) {
            $this->error('You need to add a Facebook menu payload data to your BotMan Facebook config in facebook.php.');
            exit;
        }

        $this->info("Bot config: " . (env('FACEBOOK_BOT_NAME') ?? '[no key FACEBOOK_BOT_NAME in env]'));
        $this->info("Using menu from config: {$menu_config}");
        $this->info("Using access_token: " . substr(config('botman.facebook.token'), 0, 40) . "...");
        $this->info("Using app_id: " . config('botman.facebook.app_id'));

        $response = $this->http->post(
            config('botman.facebook.graph_url') . 'me/messenger_profile?access_token=' . config('botman.facebook.token'),
            [],
            $payload,
            ["Content-Type" => "application/json"]
        );

        $responseObject = json_decode($response->getContent());

        if ($response->getStatusCode() == 200) {
            $this->info('Facebook menu was set.');
        } else {
            $this->error('Something went wrong: '.$responseObject->error->message);
        }
    }
}
