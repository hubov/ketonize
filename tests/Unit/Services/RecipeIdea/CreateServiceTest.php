<?php

namespace Tests\Unit\Services\RecipeIdea;

use App\Exceptions\ApiResultMissingPartException;
use App\Models\RecipeIdea;
use App\Models\Unit;
use App\Repositories\Interfaces\UnitRepositoryInterface;
use App\Services\Interfaces\AITextGeneratorInterface;
use App\Services\Interfaces\Recipe\RelateIngredientsToRecipeInterface;
use App\Services\RecipeIdea\CreateService;
use PHPUnit\Framework\TestCase;

class CreateServiceTest extends TestCase
{
    protected $aiResultShort = 'Ketogeniczne wegańskie gołąbki
~
150g - kasza jaglana
150g - tofu
150g - kapusta włoska
50g - cebula
3g - czosnek
15ml - olej kokosowy
1g - sól morska
1g - pieprz czarny
~
1. Kaszę ugotuj według instrukcji na opakowaniu.
2. W międzyczasie, cebulę i czosnek posiekaj, a tofu posiekaj lub rozetrzyj widelcem.
3. Na patelni rozgrzej olej kokosowy i podsmaż cebulę i czosnek przez około 2-3 minuty.
4. Dodaj do patelni tofu i smaż przez kolejne 5 minut.
5. Kapustę włoską poszatkuj i blanszuj przez około 3 minuty.
6. Wszystkie składniki połącz i dopraw solą i pieprzem.
7. Z powstałej masy formuj gołąbki.
8. Gotuj gołąbki w osolonej wodzie przez 20-25 minut lub dopóki nie będą miękkie.
~
90 kcal
Białko: 6g
Tłuszcz: 7g
Węglowodany netto: 8g';
    protected $aiResultLong = 'Nazwa: Ketogeniczne wegańskie gołąbki
~
Składniki:
150g - kasza jaglana
150g - tofu
150g - kapusta włoska
50g - cebula
3g - czosnek
15ml - olej kokosowy
1g - sól morska
1g - pieprz czarny
~
Opis przygotowania:
1. Kaszę ugotuj według instrukcji na opakowaniu.
2. W międzyczasie, cebulę i czosnek posiekaj, a tofu posiekaj lub rozetrzyj widelcem.
3. Na patelni rozgrzej olej kokosowy i podsmaż cebulę i czosnek przez około 2-3 minuty.
4. Dodaj do patelni tofu i smaż przez kolejne 5 minut.
5. Kapustę włoską poszatkuj i blanszuj przez około 3 minuty.
6. Wszystkie składniki połącz i dopraw solą i pieprzem.
7. Z powstałej masy formuj gołąbki.
8. Gotuj gołąbki w osolonej wodzie przez 20-25 minut lub dopóki nie będą miękkie.
~
Wartości odżywcze (w jednej porcji):
90 kcal
Białko: 6g
Tłuszcz: 7g
Węglowodany netto: 8g';
    protected $chatCompletionsService;
    protected $unitRepository;
    protected $relateIngredientsToRecipe;
    protected $createService;

    protected function setUp(): void
    {
        $this->chatCompletionsService = $this->createMock(AITextGeneratorInterface::class);
        $this->chatCompletionsService
            ->expects($this->once())
            ->method('settings')
            ->withAnyParameters()
            ->willReturnSelf();
        $this->chatCompletionsService
            ->expects($this->once())
            ->method('execute')
            ->willReturnSelf();

        $unit = new Unit();
        $unit->id = 1;

        $this->unitRepository = $this->createMock(UnitRepositoryInterface::class);
        $this->unitRepository
            ->method('getBySymbolOrName')
            ->withAnyParameters()
            ->willReturn($unit);

        $this->relateIngredientsToRecipe = $this->createMock(RelateIngredientsToRecipeInterface::class);
        $this->relateIngredientsToRecipe
            ->expects($this->once())
            ->method('setRecipe')
            ->withAnyParameters()
            ->willReturnSelf();
        $this->relateIngredientsToRecipe
            ->expects($this->atLeastOnce())
            ->method('addIngredientByName')
            ->withAnyParameters();

        $this->createService = new CreateService($this->chatCompletionsService, $this->unitRepository, $this->relateIngredientsToRecipe);
    }

    /**
     * @test
     * @dataProvider aiResultsProvider
     */
    public function returns_recipe_idea_object($aiResult, $expectedResult): void
    {
        $this->chatCompletionsService
            ->expects($this->once())
            ->method('return')
            ->willReturn($aiResult);

        $result = $this->createService
            ->setDiet(1, 1)
            ->execute('gołąbki')
            ->return();

        $this->assertIsObject($result);
        $this->assertInstanceOf(RecipeIdea::class, $result);
    }

    /**
     * @test
     * @dataProvider aiResultsProvider
     */
    public function parses_api_result($aiResult, $expectedResult): void
    {
        $this->chatCompletionsService
            ->expects($this->once())
            ->method('return')
            ->willReturn($aiResult);

        $result = $this->createService
            ->setDiet(1, 1)
            ->execute('gołąbki')
            ->return();

        $this->assertEquals($expectedResult['name'], $result->name);
        $this->assertEquals($expectedResult['description'], $result->description);
        $this->assertEquals($expectedResult['kcal'], $result->kcal);
        $this->assertEquals($expectedResult['protein'], $result->protein);
        $this->assertEquals($expectedResult['fat'], $result->fat);
        $this->assertEquals($expectedResult['carbohydrate'], $result->carbohydrate);
    }

    /**
     * @test
     * @dataProvider aiResultsProvider
     */
    public function parseApiResult_incompleteApiResult_throwsException($aiResult, $expectedResult)
    {
        $aiResultArr = explode("~", $aiResult);
        array_pop($aiResultArr);
        $corruptedAiResult = implode("~", $aiResultArr);

        $this->chatCompletionsService
            ->expects($this->once())
            ->method('return')
            ->willReturn($corruptedAiResult);

        $this->expectException(ApiResultMissingPartException::class);
        $result = $this->createService
            ->setDiet(1, 1)
            ->execute('gołąbki')
            ->return();
    }
    public function aiResultsProvider(): array
    {
        $result = [
            'name' => 'Ketogeniczne wegańskie gołąbki',
            'description' => '1. Kaszę ugotuj według instrukcji na opakowaniu.
2. W międzyczasie, cebulę i czosnek posiekaj, a tofu posiekaj lub rozetrzyj widelcem.
3. Na patelni rozgrzej olej kokosowy i podsmaż cebulę i czosnek przez około 2-3 minuty.
4. Dodaj do patelni tofu i smaż przez kolejne 5 minut.
5. Kapustę włoską poszatkuj i blanszuj przez około 3 minuty.
6. Wszystkie składniki połącz i dopraw solą i pieprzem.
7. Z powstałej masy formuj gołąbki.
8. Gotuj gołąbki w osolonej wodzie przez 20-25 minut lub dopóki nie będą miękkie.',
            'kcal' => 90,
            'protein' => 6,
            'fat' => 7,
            'carbohydrate' => 8,
        ];

        return [
            'short AI result' => [$this->aiResultShort, $result],
            'long AI result' => [$this->aiResultLong, $result]
        ];
    }
}
