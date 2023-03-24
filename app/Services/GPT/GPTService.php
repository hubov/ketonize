<?php

namespace App\Services\GPT;

use App\Services\Interfaces\AIGeneratorInterface;
use Tectalic\OpenAi\Client;

abstract class GPTService implements AIGeneratorInterface
{
    protected $client;
    protected $strategy;
    protected $attributes;
    protected $resultModel;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function settings(array $attributes): AIGeneratorInterface
    {
        $this->attributes = array_replace($this->attributes, $attributes);

        return $this;
    }

    public function execute(): AIGeneratorInterface
    {
        $strategy = $this->strategy;

        $this->resultModel = $this->client
            ->$strategy()
            ->create($this->attributes)
            ->toModel();

        return $this;
    }

    public function return(): string|array
    {
        return $this->strategy;
    }
}
