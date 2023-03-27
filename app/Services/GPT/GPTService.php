<?php

namespace App\Services\GPT;

use App\Exceptions\AIServiceUnavailableException;
use App\Services\Interfaces\AIGeneratorInterface;
use Illuminate\Support\Facades\Log;
use Tectalic\OpenAi\Client;
use Tectalic\OpenAi\ClientException;

abstract class GPTService implements AIGeneratorInterface
{
    protected $client;
    protected $strategy;
    protected $attributes;
    protected $resultModel;

    public function __construct(Client $client)
    {
        $this->client = $client;
        $this->attributes = [
            'max_tokens' => 2000,
        ];
    }

    public function settings(array $attributes): AIGeneratorInterface
    {
        $this->attributes = array_replace($this->attributes, $attributes);

        return $this;
    }

    public function execute(): AIGeneratorInterface
    {
        $strategy = $this->strategy;

        try {
            $this->resultModel = $this->client
                ->$strategy()
                ->create($this->attributes)
                ->toModel();

            return $this;
        } catch (ClientException $e) {
            Log::error('GPT API error: ', ['message' => $e->getMessage()]);

            throw(new AIServiceUnavailableException());
        }
    }

    public function return(): string|array
    {
        return $this->strategy;
    }
}
