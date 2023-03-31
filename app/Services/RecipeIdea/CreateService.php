<?php

namespace App\Services\RecipeIdea;

use App\Exceptions\ApiResultIngredientMissingException;
use App\Exceptions\ApiResultMissingPartException;
use App\Models\RecipeIdea;
use App\Repositories\Interfaces\UnitRepositoryInterface;
use App\Services\Interfaces\AIGeneratorInterface;
use App\Services\Interfaces\Recipe\RelateIngredientsToRecipeInterface;
use App\Services\Interfaces\RecipeIdeaInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Str;

class CreateService implements RecipeIdeaInterface
{
    protected $aiService;
    protected $unitRepository;
    protected $relateIngredientsToRecipe;
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
    protected $recipeIdea;
    protected $macroLabels = [
        'Białko' => 'protein',
        'Tłuszcz' => 'fat',
        'Węglowodany netto' => 'carbohydrate',
        'Węglowodany' => 'carbohydrate',
    ];

    public function __construct(AIGeneratorInterface $aiService, UnitRepositoryInterface $unitRepository, RelateIngredientsToRecipeInterface $relateIngredientsToRecipe)
    {
        $this->aiService = $aiService;
        $this->unitRepository = $unitRepository;
        $this->relateIngredientsToRecipe = $relateIngredientsToRecipe;
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
        $this->createIdea();

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
            $this->parsedAiResult['description'] = $this->parseDescription($aiResultArray);
            $macros = $this->parseMacros($aiResultArray);
            $this->parsedAiResult['kcal'] = $macros['kcal'];
            $this->parsedAiResult['protein'] = $macros['protein'];
            $this->parsedAiResult['fat'] = $macros['fat'];
            $this->parsedAiResult['carbohydrate'] = $macros['carbohydrate'];
        } else {
            throw new ApiResultMissingPartException();
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
        $ingredients = trim($aiResultArray[1]);

        if (Str::length($ingredients) > 0) {
            $ingredients = explode("\n", trim($aiResultArray[1]));

            if (count($ingredients) > 0) {
                $this->removeHeader($ingredients);

                foreach ($ingredients as $ingredient) {
                    $ingredientsList[] = $this->parseIngredientsListElement($ingredient);
                }
            }
        }

        if (count($ingredientsList) == 0) {
            throw new ApiResultIngredientMissingException();
        }

        return $ingredientsList;
    }

    protected function removeHeader(array &$array)
    {
        if (!Str::startsWith(trim($array[0]), '1.')) {
            if (Str::contains($array[0], ':')) {
                unset($array[0]);
            }
        }
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

    protected function parseDescription(array $aiResultArray): string
    {
        $descriptionArray = explode("\n", trim($aiResultArray[2]));
        if (count($descriptionArray) > 0) {
            $this->removeHeader($descriptionArray);

            $description = implode("\n", $descriptionArray);
        } else {
            // THROW EXCEPTION missing description
        }

        return $description;
    }

    protected function parseMacros(array $aiResultArray): array
    {
        $macrosArray = explode("\n", trim($aiResultArray[3]));

        $result = [];

        if (count($macrosArray) > 3) {
            foreach ($macrosArray as $singleMacro) {
                $singleMacro = trim($singleMacro);
                $result = array_merge($result, $this->retrieveMacro($singleMacro));
            }
        } else {
            // THROW EXCEPTION wrong macros format
        }

        if (count($result) != 4) {
            // THROW EXCEPTION missing macros
        }

        return $result;
    }

    protected function retrieveMacro(string $row): array
    {
        $result = [];

        if ($parsedKcal = $this->findKcal($row)) {
            $result['kcal'] = $this->retrieveKcal($parsedKcal);
        } else {
            $result = array_merge($result, $this->retrieveSingleMacro($row));
        }

        return $result;
    }

    protected function findKcal(string $row): string|bool
    {
        if (preg_match('/\d+ kcal/u', $row, $parsedKcal)) {
            return $parsedKcal[0];
        }

        return false;
    }

    protected function retrieveKcal(string $haystack): int
    {
        preg_match('/\d+/u', $haystack, $parsedKcal);

        return $parsedKcal[0];
    }

    protected function retrieveSingleMacro(string $haystack): array
    {
        $result = [];

        foreach ($this->macroLabels as $label => $macroType) {
            if ($parsedMacro = $this->findMacro($haystack, $label)) {
                $result[$macroType] = $this->retrieveMacroValue($parsedMacro);
            }
        }

        return $result;
    }

    protected function findMacro(string $haystack, string $label): string|bool
    {
        if (preg_match('/'. $label . ': \d+g/u', $haystack, $parsedMacro)) {
            return $parsedMacro[0];
        }

        return false;
    }

    protected function retrieveMacroValue(string $parsedMacro)
    {
        preg_match('/\d+/u', $parsedMacro, $retrievedMacro);

        return $retrievedMacro[0];
    }

    public function return(): RecipeIdea
    {
        return $this->recipeIdea;
    }

    public function recipeIdeaModel()
    {
        return new RecipeIdea();
    }

    public function createIdea()
    {
        $this->recipeIdea = $this->recipeIdeaModel();
        $this->recipeIdea->name = $this->parsedAiResult['name'];
        $this->relateIngredientsToRecipe
            ->setRecipe($this->recipeIdea);
        foreach ($this->parsedAiResult['ingredients'] as $ingredient) {
            $this->relateIngredientsToRecipe
                ->addIngredientByName($ingredient['name'], $ingredient['amount'], $ingredient['unit']);
        }
        $this->recipeIdea->description = $this->parsedAiResult['description'];
        $this->recipeIdea->kcal = $this->parsedAiResult['kcal'];
        $this->recipeIdea->protein = $this->parsedAiResult['protein'];
        $this->recipeIdea->fat = $this->parsedAiResult['fat'];
        $this->recipeIdea->carbohydrate = $this->parsedAiResult['carbohydrate'];

    }
}
