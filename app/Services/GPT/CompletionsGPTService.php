<?php

namespace App\Services\GPT;

use App\Exceptions\AIServiceUnavailableException;
use Illuminate\Support\Facades\Log;
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
        try {
            $result = '';
            foreach ($this->resultModel->choices as $choice) {
                $result .= $choice->text;
            }

            return $result;
        } catch (\Exception $e) {
            Log::error('GPT API response error: ', ['message' => $e->getMessage()]);
            throw(new AIServiceUnavailableException());
        }
    }
}
