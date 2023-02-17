<?php

namespace Tests\Unit\Services\File\Factories;

use App\Services\File\Factories\JpgSaver;
use App\Services\File\Factories\SaverFactory;
use App\Services\File\Factories\WebpSaver;
use PHPUnit\Framework\TestCase;

class SaverFactoryTest extends TestCase
{
    /**
     * @test
     * @dataProvider saversProvider
     */
    public function returns_file_saver_by_given_file_encoding_format($expectedValue, $fileFormat)
    {
        $saverFactory = new SaverFactory();

        $this->assertEquals(
            $expectedValue,
            $saverFactory->get($fileFormat)
        );
    }

    protected function saversProvider()
    {
        return [
            [
                new JpgSaver(),
                'jpg'
            ],
            [
                new WebpSaver(),
                'webp'
            ]
        ];
    }
}
