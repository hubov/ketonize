<?php

namespace Tests\Unit\Services;

use App\Services\ImageParser;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
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
}
