<?php

namespace Tests\Unit\Services;

use App\Services\ImageParser;
use PHPUnit\Framework\TestCase;

class ImageParserTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }

    /** @test */
    public function generates_file_name_with_salt(): void
    {
        $imageParser = new ImageParser($this->image);

        $expectedValue = 'recipe-1-XXXXXX';

        $result = $imageParser->getName('Recipe #1');

        $this->assertStringStartsWith('recipe-1-', $result);
        $this->assertEquals(strlen($expectedValue), strlen($result));
    }
}
