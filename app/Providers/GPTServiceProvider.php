<?php

namespace App\Providers;

use App\Services\GPTService;
use App\Services\Interfaces\AIGeneratorInterface;
use Illuminate\Support\ServiceProvider;
use Symfony\Component\HttpClient\Psr18Client;
use Tectalic\OpenAi\Authentication;
use Tectalic\OpenAi\Client;
use Tectalic\OpenAi\Manager;

class GPTServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->app->bind(AIGeneratorInterface::class, function()
        {
            $auth = $this->app->makeWith(Authentication::class, getenv('OPENAI_API_KEY'));
            $httpClient = $this->app->make(Psr18Client::class);

//            $client = new Client($httpClient, $auth, Manager::BASE_URI);
            $client = $this->app->makeWith(Client::class, [$httpClient, $auth, Manager::BASE_URI]);

            return new GPTService($client);
        });
    }
}
