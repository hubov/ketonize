<?php

namespace Tests\Unit\Services\RecipeIdea;

use App\Models\RecipeIdea;
use App\Services\Interfaces\AITextGeneratorInterface;
use App\Services\RecipeIdea\CreateService;
use PHPUnit\Framework\TestCase;

class CreateServiceTest extends TestCase
{
    protected $aiResult;
    protected $chatCompletionsService;

    protected function setUp(): void
    {
        $this->aiResult = 'Ketogeniczne wegańskie gołąbki
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
        $this->chatCompletionsService
            ->expects($this->once())
            ->method('return')
            ->willReturn($this->aiResult);
    }

    /** @test */
    public function returns_recipe_idea_object(): void
    {
        $createService = new CreateService($this->chatCompletionsService);
        $result = $createService
            ->setDiet(1, 1)
            ->execute('gołąbki')
            ->return();

        $this->assertIsObject($result);
        $this->assertInstanceOf(RecipeIdea::class, $result);


    }
}
