<?php

namespace Tests\Unit\Services\RecipeIdea;

use App\Models\RecipeIdea;
use App\Services\Interfaces\AITextGeneratorInterface;
use App\Services\RecipeIdea\CreateService;
use Illuminate\Support\Collection;
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

        $createService = new CreateService($this->chatCompletionsService);
        $result = $createService
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
    public function parses_title($aiResult, $expectedResult): void
    {
        $this->chatCompletionsService
            ->expects($this->once())
            ->method('return')
            ->willReturn($aiResult);

        $createService = new CreateService($this->chatCompletionsService);
        $result = $createService
            ->setDiet(1, 1)
            ->execute('gołąbki')
            ->return();

        $this->assertEquals($expectedResult['name'], $result->name);
    }

    /**
     * @test
     * @dataProvider aiResultsProvider
     */
    public function parses_ingredients($aiResult, $expectedResult): void
    {
        $this->chatCompletionsService
            ->expects($this->once())
            ->method('return')
            ->willReturn($aiResult);

        $createService = new CreateService($this->chatCompletionsService);
        $result = $createService
            ->setDiet(1, 1)
            ->execute('gołąbki')
            ->return();

        $this->assertInstanceOf(Collection::class, $result->ingredients);
    }

    public function aiResultsProvider(): array
    {
        $result = [
            'name' => 'Ketogeniczne wegańskie gołąbki'
        ];

        return [
            'short AI result' => [$this->aiResultShort, $result],
            'long AI result' => [$this->aiResultLong, $result]
        ];
    }
}
