<?php

namespace App\Services\RecipeIdea;

use App\Models\RecipeIdea;
use App\Services\Interfaces\AIGeneratorInterface;
use App\Services\Interfaces\RecipeIdeaInterface;

class CreateService implements RecipeIdeaInterface
{
    protected $aiService;

    public function __construct(AIGeneratorInterface $aiService)
    {
        $this->aiService = $aiService;
    }

    public function return(): RecipeIdea
    {
        return new RecipeIdea();
    }
}
