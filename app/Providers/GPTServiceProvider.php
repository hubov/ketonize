<?php

namespace App\Providers;

use App\Services\GPT\CompletionsGPTService;
use App\Services\Interfaces\AITextGeneratorInterface;
use Illuminate\Support\ServiceProvider;
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
        $this->app->singleton(Client::class, function($app) {
            if (Manager::isGlobal()) {
                return Manager::access();
            }

            $auth = new Authentication(config('services.openai.token'));
            $httpClient = new \GuzzleHttp\Client();
            return Manager::build($httpClient, $auth);
        });

        $this->app->bind(AITextGeneratorInterface::class, CompletionsGPTService::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
