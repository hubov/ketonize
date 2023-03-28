<?php

namespace Tests\Unit\Services\RecipeIdea;

use App\Models\RecipeIdea;
use App\Services\Interfaces\AITextGeneratorInterface;
use App\Services\RecipeIdea\CreateService;
use PHPUnit\Framework\TestCase;

class CreateServiceTest extends TestCase
{
    /** @test */
    public function returns_recipe_idea_object(): void
    {
        $chatCompletionsService = $this->createMock(AITextGeneratorInterface::class);

        $createService = new CreateService($chatCompletionsService);

        $this->assertEquals(new RecipeIdea(), $createService->return());
    }
}
