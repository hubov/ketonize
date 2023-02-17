<?php

namespace Tests\Unit\Services\Image;

use App\Services\File\Saver;
use App\Services\File\SaverFactory;
use App\Services\Image\Factories\ImageFactory;
use App\Services\Image\RecipeImageProcessor;
use App\Services\Interfaces\Image\ImageFactoryInterface;
use Illuminate\Http\UploadedFile;
use Intervention\Image\Image;
use Intervention\Image\ImageManager;
use Tests\TestCase;

class RecipeImageProcessorTest extends TestCase
{
    public $imageManager;
    public $imageFactory;
    public $saverFactory;
    public $imageParser;

    public function setUp(): void
    {
        parent::setUp();

        $this->imageManager = $this->createMock(ImageManager::class);
        $this->imageFactory = $this->createMock(ImageFactory::class);
        $this->saverFactory = $this->createMock(SaverFactory::class);

        $this->imageParser = new RecipeImageProcessor(
            $this->imageManager,
            $this->imageFactory,
            $this->saverFactory
        );
    }

    /** @test */
    public function generates_file_name_with_salt(): void
    {
        $expectedValue = 'recipe-1-XXXXXX';

        $result = $this->imageParser->getName('Recipe #1');

        $this->assertStringStartsWith('recipe-1-', $result);
        $this->assertEquals(strlen($expectedValue), strlen($result));
    }

    /** @test */
    public function processes__uploaded_image()
    {
        $rawImage = UploadedFile::fake()->image('recipe-1.jpg', 4032, 3024)->size(1950);
        $this->imageParser->getName('Recipe 1');

        $image = $this->getMockBuilder(Image::class)
            ->onlyMethods(['width'])
            ->onlyMethods(['save'])
            ->addMethods(['insert'])
            ->addMethods(['resize'])
            ->getMock();

        $image
            ->expects($this->once())
            ->method('insert')
            ->withAnyParameters()
            ->willReturn($image);

        $this->imageManager
            ->expects($this->exactly(2))
            ->method('make')
            ->withConsecutive(
                [$rawImage],
                [$this->imageParser->getWatermarkFilePath()]
            )
            ->willReturn($image);

        $imageFactoryInstance = $this->createMock(ImageFactoryInterface::class);
        $this->imageFactory
            ->expects($this->exactly(3))
            ->method('get')
            ->withConsecutive(
                ['RecipeCover'],
                ['RecipeCover'],
                ['RecipeThumbnail']
            )
            ->willReturn($imageFactoryInstance);

        $imageFactoryInstance
            ->expects($this->exactly(3))
            ->method('generate')
            ->with($image)
            ->willReturn($image);

        $saver = $this->createMock(Saver::class);
        $this->saverFactory
            ->expects($this->exactly(5))
            ->method('get')
            ->withConsecutive(
                ['jpg'],
                ['jpg'],
                ['webp'],
                ['jpg'],
                ['webp']
            )
            ->willReturn($saver);
        $saver
            ->expects($this->exactly(5))
            ->method('save')
            ->withConsecutive(
                [$image, $this->imageParser->getLocalImageFilePath()],
                [$image, $this->imageParser->getPublicImageFilePath('cover')],
                [$image, $this->imageParser->getPublicImageFilePath('cover')],
                [$image, $this->imageParser->getPublicImageFilePath('thumbnail')],
                [$image, $this->imageParser->getPublicImageFilePath('thumbnail')]
            )
            ->willReturn(true);

        $result = $this->imageParser
            ->generate($rawImage);

        $this->assertTrue($result);
    }
}
