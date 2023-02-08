<?php

namespace Tests\Unit\Services;

use App\Services\ImageParser;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Image;
use Intervention\Image\ImageManager;
use Tests\TestCase;

class ImageParserTest extends TestCase
{
    public $imageManager;

    public function setUp(): void
    {
        parent::setUp();

        $this->imageManager = $this->createMock(ImageManager::class);
    }

    /** @test */
    public function generates_file_name_with_salt(): void
    {
        $imageParser = new ImageParser($this->imageManager);

        $expectedValue = 'recipe-1-XXXXXX';

        $result = $imageParser->getName('Recipe #1');

        $this->assertStringStartsWith('recipe-1-', $result);
        $this->assertEquals(strlen($expectedValue), strlen($result));
    }

    /** @test */
    public function keeps_original_uploaded_file()
    {
        $storage = Storage::fake('local_recipe_images');

        $rawImage = UploadedFile::fake()->image('recipe-1.jpg', 4032, 3024)->size(1950);
        $tmpFile = $rawImage->getFilename();

        $imageParser = new ImageParser($this->imageManager);
        $imageName = $imageParser->getName('Recipe #1');
        $result = $imageParser
            ->setFile($rawImage)
            ->keepOriginal();

        $this->assertEquals($imageParser, $result);
        Storage::disk('local_recipe_images')->assertExists($imageName . '.' . $rawImage->extension());
    }

    /** @test */
    public function makes_recipe_cover(): void
    {
        Storage::fake('public_recipe_images');
        $path = Storage::disk('public_recipe_images')->path('');
        $rawImage = UploadedFile::fake()->image('recipe-1.jpg', 4032, 3024)->size(1950);

        $newImage = $this->getMockBuilder(Image::class)
            ->onlyMethods(['save'])
            ->addMethods(['resize'])
            ->getMock();

        $this->imageManager
            ->expects($this->once())
            ->method('make')
            ->with($rawImage->getPathname())
            ->willReturn($newImage);
        $newImage
            ->expects($this->once())
            ->method('resize')
            ->with(2560, null)
            ->willReturnSelf();

        $imageParser = new ImageParser($this->imageManager);
        $imageName = $imageParser->getName('Recipe 1');

        $newImage
            ->expects($this->at(1))
            ->method('save')
            ->with($path . $imageName . '.jpg', 100)
            ->willReturnSelf();

        $newImage
            ->expects($this->at(2))
            ->method('save')
            ->with($path . $imageName . '.webp', 100)
            ->willReturnSelf();

        $result = $imageParser
            ->setFile($rawImage)
            ->makeRecipeCover();

        $this->assertTrue($result);
//        Storage::disk('public_recipe_images')->assertExists($imageName . '.jpg');
//        Storage::disk('public_recipe_images')->assertExists($imageName . '.webp');
    }
}
