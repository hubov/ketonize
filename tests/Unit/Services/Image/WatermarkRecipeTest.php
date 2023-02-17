<?php

namespace Tests\Unit\Services\Image;

use App\Services\Image\Factories\RecipeCover;
use App\Services\Image\WatermarkRecipe;
use Intervention\Image\Image;
use PHPUnit\Framework\TestCase;

class WatermarkRecipeTest extends TestCase
{
    /** @test */
    public function inserts_watermark_to_given_image()
    {
        $image = $this->getMockBuilder(Image::class)
            ->onlyMethods(['width'])
            ->addMethods(['insert', 'resize'])
            ->getMock();

        $imageFactory = $this->createMock(RecipeCover::class);

        $imageFactory
            ->expects($this->once())
            ->method('generate')
            ->with($image)
            ->willReturn($image);

        $image
            ->expects($this->once())
            ->method('resize')
            ->withAnyParameters()
            ->willReturn(new Image());

        $image
            ->expects($this->once())
            ->method('insert')
            ->withAnyParameters()
            ->willReturn($image);

        $watermark = new WatermarkRecipe(
            $imageFactory,
            $image
        );

        $this->assertEquals($image, $watermark->generate($image));
    }
}
