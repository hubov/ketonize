<?php

namespace Tests\Unit\Services\GPT;

use App\Exceptions\AIServiceUnavailableException;
use App\Services\GPT\CompletionsGPTService;
use DG\BypassFinals;
use Illuminate\Support\Facades\Log;
use PHPUnit\Framework\TestCase;
use Tectalic\OpenAi\Client;
use Tectalic\OpenAi\ClientException;
use Tectalic\OpenAi\Handlers\Completions;

class CompletionsGPTServiceTest extends TestCase
{
    protected $completionsObj;
    protected $response;
    protected $client;

    public function setUp(): void
    {
        BypassFinals::enable();

        $this->completionsObj = $this->createMock(Completions::class);

        $returnResult = new \StdClass();
        $returnResult->text = 'Test completion';

        $this->response = new \StdClass();
        $this->response->choices = [
            $returnResult
        ];

        $this->client = $this->createMock(Client::class);
        $this->client
            ->expects($this->once())
            ->method('completions')
            ->willReturn($this->completionsObj);
    }

    /** @test */
    public function returns_completion(): void
    {
        $this->completionsObj
            ->expects($this->once())
            ->method('create')
            ->withAnyParameters()
            ->willReturnSelf();
        $this->completionsObj
            ->expects($this->once())
            ->method('toModel')
            ->willReturn($this->response);

        $settings = ['prompt' => 'test prompt'];

        $completionsGptService = new CompletionsGPTService($this->client);
        $result = $completionsGptService
            ->settings($settings)
            ->execute()
            ->return();

        $this->assertEquals('Test completion', $result);
    }

    /** @test */
    public function returns_exception_if_error_occurred()
    {
        $this->completionsObj
            ->expects($this->once())
            ->method('create')
            ->withAnyParameters()
            ->willThrowException(new ClientException());

        $settings = ['prompt' => 'test prompt'];

        $completionsGptService = new CompletionsGPTService($this->client);

        $this->expectException(AIServiceUnavailableException::class);
        Log::shouldReceive('error')
            ->once();
        $completionsGptService
            ->settings($settings)
            ->execute();
    }
}
