<?php

namespace Tests\Unit\Services\GPT;

use App\Services\GPT\CompletionsGPTService;
use DG\BypassFinals;
use PHPUnit\Framework\TestCase;
use Tectalic\OpenAi\Client;
use Tectalic\OpenAi\Handlers\Completions;

class CompletionsGPTServiceTest extends TestCase
{
    /** @test */
    public function returns_completion(): void
    {
        BypassFinals::enable();

        $completionsObj = $this->createMock(Completions::class);

        $result = new \StdClass();
        $result->text = 'Test completion';

        $response = new \StdClass();
        $response->choices = [
            $result
        ];

        $client = $this->createMock(Client::class);
        $client
            ->expects($this->once())
            ->method('completions')
            ->willReturn($completionsObj);
        $completionsObj
            ->expects($this->once())
            ->method('create')
            ->withAnyParameters()
            ->willReturnSelf();
        $completionsObj->expects($this->once())
            ->method('toModel')
            ->willReturn($response);

        $settings = ['prompt' => 'test prompt'];

        $completionsGptService = new CompletionsGPTService($client);
        $result = $completionsGptService
            ->settings($settings)
            ->execute()
            ->return();

        $this->assertEquals('Test completion', $result);
    }
}
