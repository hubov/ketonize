<?php

namespace Tests\Unit\Services\GPT;

use App\Services\GPT\GPTService;
use DG\BypassFinals;
use PHPUnit\Framework\TestCase;
use Tectalic\OpenAi\Client;

class GPTServiceTest extends TestCase
{
    /** @test */
    public function returns_completion(): void
    {
        BypassFinals::enable();

        $client = $this->createMock(Client::class);

        $gptService = new GPTService($client);
        $result = $gptService
            ->prompt('test prompt')
            ->execute()
            ->return();

        $this->assertEquals('Test completion', $result);
    }
}
