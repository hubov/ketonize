<?php

namespace App\Services;

use App\Services\Interfaces\AIGeneratorInterface;
use Tectalic\OpenAi\Client;

class GPTService implements AIGeneratorInterface
{
    protected $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function prompt(string $text): AIGeneratorInterface
    {
        // TODO: Implement prompt() method.
    }

    public function return(): string
    {
        // TODO: Implement return() method.
    }
}
