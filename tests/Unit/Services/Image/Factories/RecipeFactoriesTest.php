<?php

namespace Tests\Unit\Services\Image\Factories;

use App\Services\Image\Factories\RecipeCover;
use App\Services\Image\Factories\RecipeThumbnail;
use Illuminate\Http\UploadedFile;
use Intervention\Image\ImageManager;
use PHPUnit\Framework\TestCase;

class RecipeFactoriesTest extends TestCase
{
    /**
     * @test
     * @dataProvider factoriesProvider
     * @covers \App\Services\Image\Factories\RecipeCover
     * @covers \App\Services\Image\Factories\ThumbnailCover
     */
    public function generates_image_in_correct_width($expectedValue, $factory): void
    {
            $image = (new ImageManager())->make(UploadedFile::fake()->image('recipe-1.jpg', $factory::IMAGE_WIDTH * 1.5, 3024));

            $this->assertEquals(
                $expectedValue,
                $factory
                    ->generate($image)
                    ->width()
            );
    }

    /**
     * @test
     * @dataProvider factoriesProvider
     * @covers \App\Services\Image\Factories\RecipeCover
     * @covers \App\Services\Image\Factories\ThumbnailCover
     */
    public function does_not_enlarge_image_if_original_width_lower_than_target($expectedValue, $factory)
    {
        $image = (new ImageManager())->make(UploadedFile::fake()->image('recipe-1.jpg', $factory::IMAGE_WIDTH * 0.5, 3024));

        $this->assertEquals($factory::IMAGE_WIDTH * 0.5, $factory->generate($image)->width());
    }

    protected function factoriesProvider(): array
    {
        $cover = new RecipeCover();
        $thumbnail = new RecipeThumbnail();

        return [
            'RecipeCover' => [
                $cover::IMAGE_WIDTH,
                $cover
            ],
            'RecipeCover' => [
                $thumbnail::IMAGE_WIDTH,
                $thumbnail
            ]
        ];
    }
}
