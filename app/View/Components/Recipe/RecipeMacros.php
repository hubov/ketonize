<?php

namespace App\View\Components\Recipe;

use Illuminate\View\Component;

class RecipeMacros extends Component
{
    /**
     * The recipe protein.
     *
     * @var integer
     */
    public $protein;

    /**
     * The recipe fat.
     *
     * @var integer
     */
    public $fat;

    /**
     * The recipe carbohydrate.
     *
     * @var integer
     */
    public $carbohydrate;

    /**
     * The recipe kcal.
     *
     * @var integer
     */
    public $kcal;

    /**
     * The recipe total weight.
     *
     * @var integer
     */
    public $weightTotal;

    /**
     * The recipe preparation time.
     *
     * @var integer
     */
    public $preparationTime;

    /**
     * The recipe cooking time.
     *
     * @var integer
     */
    public $cookingTime;

    /**
     * Create a new component instance.
     *
     * @param integer protein
     * @param integer fat
     * @param integer carbohydrate
     * @param integer kcal
     * @param integer weightTotal
     * @param integer preparationTime
     * @param integer cookingTime
     * @return void
     */

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($protein = NULL, $fat = NULL, $carbohydrate = NULL, $kcal = NULL, $weightTotal = NULL, $preparationTime = NULL, $cookingTime = NULL)
    {
        $this->protein = $protein;
        $this->fat = $fat;
        $this->carbohydrate = $carbohydrate;
        $this->kcal = $kcal;
        $this->weightTotal = $weightTotal;
        $this->preparationTime = $preparationTime;
        $this->cookingTime = $cookingTime;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.recipe.recipe-macros');
    }
}
