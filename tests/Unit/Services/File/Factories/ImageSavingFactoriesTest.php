<?php

namespace Tests\Unit\Services\File\Factories;

use App\Services\File\Factories\JpgSaver;
use App\Services\File\Factories\WebpSaver;
use Intervention\Image\Image;
use PHPUnit\Framework\TestCase;

class ImageSavingFactoriesTest extends TestCase
{
    /**
     * @test
     * @dataProvider saversProvider
     * @covers \App\Services\File\Factories\JpgSaver
     * @covers \App\Services\File\Factories\WebpSaver
     */
    public function saves_image($saver)
    {
        $image = $this->createMock(Image::class);

        $image
            ->expects($this->once())
            ->method('save')
            ->withAnyParameters()
            ->willReturn(true);

        $this->assertTrue(
            $saver->save($image, 'some/path')
        );
    }

    protected function saversProvider()
    {
        return [
            [
                new JpgSaver()
            ],
            [
                new WebpSaver()
            ]
        ];
    }
}
