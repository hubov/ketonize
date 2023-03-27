<?php

namespace App\Services\GPT;

use App\Exceptions\AIServiceUnavailableException;
use App\Services\Interfaces\AIGeneratorInterface;
use Illuminate\Support\Facades\Log;
use Tectalic\OpenAi\Client;
use Tectalic\OpenAi\Models\ChatCompletions\CreateRequestMessagesItem;

class ChatCompletionsGPTService extends GPTService
{
    protected $client;
    protected $strategy;
    protected $attributes;
    protected $resultModel;

    public function __construct(Client $client)
    {
        parent::__construct($client);

        $this->strategy = 'chatCompletions';
        parent::settings([
            'model' => 'gpt-3.5-turbo',
            'temperature' => 0.3
        ]);
    }

    public function return(): string
    {
        try {
            $result = '';
            foreach ($this->resultModel->choices as $choice) {
                $result .= $choice->message->content;
            }

            return $result;
        } catch (\Exception $e) {
            Log::error('GPT API response error: ', ['message' => $e->getMessage()]);
            throw(new AIServiceUnavailableException());
        }
    }
}
