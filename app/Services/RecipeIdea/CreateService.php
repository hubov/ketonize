<?php

namespace App\Services\RecipeIdea;

use App\Models\RecipeIdea;
use App\Services\Interfaces\AIGeneratorInterface;
use App\Services\Interfaces\RecipeIdeaInterface;

class CreateService implements RecipeIdeaInterface
{
    protected $aiService;
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

    public function __construct(AIGeneratorInterface $aiService)
    {
        $this->aiService = $aiService;
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
        } else {

        }
    }

    protected function parseTitle(array $aiResultArray): string
    {
        $title = trim($aiResultArray[0]);

        return $title;
    }
    public function return(): RecipeIdea
    {
        $recipeIdea = new RecipeIdea();
        $recipeIdea->name = $this->parsedAiResult['name'];

        return $recipeIdea;
    }
}
