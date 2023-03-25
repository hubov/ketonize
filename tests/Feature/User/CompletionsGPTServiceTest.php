<?php

namespace Tests\Feature\User;

use App\Services\GPT\CompletionsGPTService;
use DG\BypassFinals;
use Http\Mock\Client;
use Psr\Http\Message\ResponseInterface;
use Tectalic\OpenAi\Authentication;
use Tests\TestCase;

class CompletionsGPTServiceTest extends TestCase
{
    protected $response;

    public function setUp(): void
    {
        parent::setUp();

        $this->response = '{
  "id": "cmpl-uqkvlQyYK7bGYrRHQ0eXlWi7",
  "object": "text_completion",
  "created": 1589478378,
  "model": "text-davinci-003",
  "choices": [
    {
      "text": "\n\nThis is indeed a test",
      "index": 0,
      "logprobs": null,
      "finish_reason": "length"
    }
  ],
  "usage": {
    "prompt_tokens": 5,
    "completion_tokens": 7,
    "total_tokens": 12
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

        $settings = ['prompt' => 'przepis na ketogeniczne wegańskie gołąbki z kapustą'];

        $completionsGptService = new CompletionsGPTService($client);
        $result = $completionsGptService
            ->settings($settings)
            ->execute()
            ->return();

        $this->assertEquals('This is indeed a test', trim($result));
    }
}
