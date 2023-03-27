<?php

namespace Tests\Feature\User\GPT;

use App\Services\GPT\ChatCompletionsGPTService;
use DG\BypassFinals;
use Http\Mock\Client;
use Psr\Http\Message\ResponseInterface;
use Tectalic\OpenAi\Authentication;
use Tests\TestCase;

class ChatCompletionsGPTServiceTest extends TestCase
{
    protected $response;

    public function setUp(): void
    {
        parent::setUp();

        $this->response = '{
  "id": "chatcmpl-123",
  "object": "chat.completion",
  "model": "gpt-3.5-turbo",
  "created": 1677652288,
  "choices": [{
    "index": 0,
    "message": {
      "role": "assistant",
      "content": "\n\nA test reply."
    },
    "finish_reason": "stop"
  }],
  "usage": {
    "prompt_tokens": 9,
    "completion_tokens": 12,
    "total_tokens": 21
  }
}';
    }

    /** @test */
    public function returns_completion(): void
    {
        BypassFinals::enable();

        $responseObj = $this->createMock(ResponseInterface::class);
        $responseObj
            ->expects($this->exactly(2))
            ->method('getStatusCode')
            ->willReturn(200);
        $responseObj
            ->expects($this->once())
            ->method('getHeaderLine')
            ->with('Content-Type')
            ->willReturn('application/json');
        $responseObj
            ->expects($this->once())
            ->method('getBody')
            ->willReturn($this->response);

        $httpClient = $this->createPartialMock(Client::class, ['sendRequest']);

        $httpClient
            ->expects($this->once())
            ->method('sendRequest')
            ->withAnyParameters()
            ->willReturn($responseObj);

        $client = new \Tectalic\OpenAi\Client($httpClient, new Authentication('token'), 'https://api.openai.com/v1/');

        $settings = [
            'messages' => [
                [
                    'role' => 'user',
                    'content' => 'gołąbki z kapustą'
                ]
            ]
        ];

        $completionsGptService = new ChatCompletionsGPTService($client);
        $result = $completionsGptService
            ->settings($settings)
            ->execute()
            ->return();

        $this->assertEquals('A test reply.', trim($result));
    }
}
