<?php

namespace Tests\Unit\Services;

use App\Services\GPTService;
use PHPUnit\Framework\TestCase;
use Tectalic\OpenAi\Client;

class GPTServiceTest extends TestCase
{
    /** @test */
    public function returns_completion(): void
    {
        $client = $this->createMock(Client::class);

        $gptService = new GPTService($client);
        $result = $gptService
            ->prompt('test prompt')
            ->return();

        $this->assertEquals('Test completion', $result);
    }
}
