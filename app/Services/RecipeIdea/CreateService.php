<?php

namespace App\Services\RecipeIdea;

use App\Models\RecipeIdea;
use App\Repositories\Interfaces\UnitRepositoryInterface;
use App\Services\Interfaces\AIGeneratorInterface;
use App\Services\Interfaces\RecipeIdeaInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Str;

class CreateService implements RecipeIdeaInterface
{
    protected $aiService;
    protected $unitRepository;
    protected $settings;
    protected $context = [
        'start' => 'napisz przepis na ',
        'carbsType' => '',
        'dietType' => '',
        'finish' => ' alternatywę dania o nazwie podanej przez użytkownika. Ilość składników podaj w gramach lub mililitrach. Temperaturę w Celsjuszach. Podaj odpowiedź według wzoru poniżej:
"""
Ketogeniczne wegańskie ravioli
~
200g - mąka migdałowa
50g - masło
75g - szpinak
3g - czosnek
1g - sól morska
1g - pieprz czarny
~
1. Połącz mąkę z masłem i wyrób ciasto.
2. Czosnek podsmaż, dodaj szpinak i przyprawy.
3. Rozwałkuj ciasto i zawiń w nim smażony szpinak.
~
90 kcal
Białko: 6g
Tłuszcz: 10g
Węglowodany netto: 4g
"""'
    ];
    protected $aiResult;
    protected $parsedAiResult;

    public function __construct(AIGeneratorInterface $aiService, UnitRepositoryInterface $unitRepository)
    {
        $this->aiService = $aiService;
        $this->unitRepository = $unitRepository;
    }

    public function setDiet(int $carbsId, int $dietTypeId): self
    {
        // get $carbs by id
        $carbs = 'ketogeniczną';

        // get $dietType by id
        $dietType = 'wegańską';

        $this->context['carbsType'] = ' ' . $carbs . ' ';
        $this->context['dietType'] = ' ' . $dietType . ' ';

        return $this;
    }

    public function execute(string $name): self
    {
        $settings = [
            'messages' => $this->setMessages($name)
        ];

        $this->aiResult = $this->aiService
            ->settings($settings)
            ->execute()
            ->return();

        $this->parseAiResult();

        return $this;
    }

    protected function setMessages(string $name): array
    {
       return  [
            [
                'role' => 'system',
                'content' => implode('', $this->context)
            ],
            [
                'role' => 'user',
                'content' => trim($name)
            ]
        ];
    }

    protected function parseAiResult(): void
    {
        $aiResultArray = explode('~', $this->aiResult);

        if (count($aiResultArray) == 4) {
            $this->parsedAiResult['name'] = $this->parseTitle($aiResultArray);
            $this->parsedAiResult['ingredients'] = $this->parseIngredients($aiResultArray);

        } else {
            // THROW EXCEPTION wrong api result format
        }
    }

    protected function parseTitle(array $aiResultArray): string
    {
        $title = trim($aiResultArray[0]);

        if (Str::contains($title, ':')) {
            $title = trim(substr($title, (strpos($title, ':') + 1)));
        }

        return $title;
    }

    protected function parseIngredients(array $aiResultArray): array
    {
        $ingredientsList = [];
        $ingredients = explode("\n", trim($aiResultArray[1]));

        if (count($ingredients) > 0) {
            foreach ($ingredients as $ingredient) {
                $ingredientsList[] = $this->parseIngredientsListElement($ingredient);
            }
        }

        return $ingredientsList;
    }

    protected function parseIngredientsListElement(string $ingredient): array
    {
        $result = [];

        $ingredientExploded = explode('-', $ingredient);
        if (count($ingredientExploded) == 2) {
            return [
                'name' => $this->parseIngredientName($ingredientExploded[1]),
                'amount' => $this->parseIngredientAmount($ingredientExploded[0]),
                'unit' => $this->parseIngredientUnit($ingredientExploded[0])
            ];
        } else {
            // THROW EXCEPTION wrong ingredients list element format
        }
    }

    protected function parseIngredientAmount(string $rawAmount): int
    {
        preg_match('/^\d+/', trim($rawAmount), $amountOutput);
        if (isset($amountOutput[0])) {
            return (int)$amountOutput[0];
        } else {
            // THROW EXCEPTION wrong ingredient amount format
        }
    }

    protected function parseIngredientUnit(string $rawAmount): int
    {
        if (preg_match('/\d+\s*\pL+\.?/', trim($rawAmount), $unitOutput)) {
            preg_match('/\pL+\.?/', trim($unitOutput[0]), $unitOutput);
        } else {
            // THROW EXCEPTION wrong ingredient amount format
        }
        if (isset($unitOutput[0])) {
            return $this->retrieveUnit(trim($unitOutput[0]));
        } else {
            // THROW EXCEPTION wrong ingredient amount format
        }
    }

    protected function retrieveUnit(string $rawUnit): int
    {
        try {
            $unit = $this->unitRepository->getBySymbolOrName($rawUnit);

            return $unit->id;
        } catch (ModelNotFoundException $e) {
            // THROW EXCEPTION symbol not found
        }
    }

    protected function parseIngredientName(string $rawName): string
    {
        return trim($rawName);
    }

    public function return(): RecipeIdea
    {
        $recipeIdea = new RecipeIdea();
        $recipeIdea->name = $this->parsedAiResult['name'];

        return $recipeIdea;
    }
}
