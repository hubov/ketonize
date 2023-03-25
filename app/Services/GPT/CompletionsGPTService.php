<?php

namespace App\Services\GPT;

use Tectalic\OpenAi\Client;

class CompletionsGPTService extends GPTService
{
    protected $client;
    protected $strategy;
    protected $attributes;
    protected $resultModel;

    public function __construct(Client $client)
    {
        parent::__construct($client);

        $this->strategy = 'completions';
        $this->settings([
            'model' => 'text-davinci-003'
        ]);
    }

    public function return(): string
    {
        $result = '';
        foreach ($this->resultModel->choices as $choice) {
            $result .= $choice->text;
        }

        return $result;
    }
}
