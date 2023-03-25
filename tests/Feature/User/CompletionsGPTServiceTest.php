<?php

namespace Tests\Feature\User;

use App\Services\GPT\CompletionsGPTService;
use DG\BypassFinals;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Http;
use Mockery\MockInterface;
use Nyholm\Psr7\Factory\Psr17Factory;
use Nyholm\Psr7\Request;
use Psr\Http\Message\ResponseInterface;
use Tectalic\OpenAi\Client;
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

        $responseObj = $this->createPartialMock(ResponseInterface::class, ['getBody', 'getStatusCode']);
        $responseObj
            ->expects($this->once())
            ->method('getStatusCode')
            ->willReturn(200);
        $responseObj
            ->expects($this->once())
            ->method('getBody')
            ->willReturn($this->response);

        $client = $this->createPartialMock(Client::class, ['sendRequest']);
        $client
            ->expects($this->once())
            ->method('sendRequest')
            ->withAnyParameters()
            ->willReturn($responseObj);

        $settings = ['prompt' => 'przepis na ketogeniczne wegańskie gołąbki z kapustą'];

        $completionsGptService = new CompletionsGPTService($client);
        $result = $completionsGptService
            ->settings($settings)
            ->execute()
            ->return();

        dd($result);
    }
}
