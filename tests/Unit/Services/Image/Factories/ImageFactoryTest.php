<?php

namespace Tests\Unit\Services\Image\Factories;

use App\Services\Image\Factories\ImageFactory;
use App\Services\Image\Factories\RecipeCover;
use App\Services\Image\Factories\RecipeThumbnail;
use PHPUnit\Framework\TestCase;

class ImageFactoryTest extends TestCase
{
    /** @test */
    public function returns_existing_image_factory()
    {
        $imageFactory = new ImageFactory();

        $this->assertEquals($imageFactory->get('RecipeCover'), new RecipeCover());
        $this->assertEquals($imageFactory->get('RecipeThumbnail'), new RecipeThumbnail());
    }
}
