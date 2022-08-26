<?php

namespace App\View\Components;

use Illuminate\View\Component;

class RecipeForm extends Component
{
    /**
     * The recipe name.
     *
     * @var string
     */
    public $name;

    /**
     * The list of measure units.
     *
     * @var \Illuminate\Database\Eloquent\Collection
     */
    public $units;

    /**
     * The recipe image.
     *
     * @var string
     */
    public $image;

    /**
     * The recipe proteins.
     *
     * @var integer
     */
    public $protein;

    /**
     * The recipe fats.
     *
     * @var integer
     */
    public $fat;

    /**
     * The recipe carbohydrates.
     *
     * @var integer
     */
    public $carbohydrate;

    /**
     * The recipe calories.
     *
     * @var integer
     */
    public $kcal;

    /**
     * The recipe ingredients.
     *
     * @var @var \Illuminate\Database\Eloquent\Collection
     */
    public $ingredients;

    /**
     * The recipe description.
     *
     * @var string
     */
    public $description;

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
     * @param string name
     * @param \Illuminate\Database\Eloquent\Collection units
     * @return void
     */
    public function __construct($name = '', $units = '', $image = '',  $protein = '', $fat = '', $carbohydrate = '', $kcal = '', $ingredients = '', $description = '', $preparationTime = '', $cookingTime = '')
    {
        $this->name = $name;
        $this->units = $units;
        $this->image = $image;
        $this->protein = $protein;
        $this->fat = $fat;
        $this->carbohydrate = $carbohydrate;
        $this->kcal = $kcal;
        $this->ingredients = $ingredients;
        $this->description = $description;
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
        return view('components.recipe-form');
    }
}
