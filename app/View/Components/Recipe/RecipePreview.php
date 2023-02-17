<?php

namespace App\View\Components\Recipe;

use Illuminate\View\Component;

class RecipePreview extends Component
{
    /**
     * The recipe name.
     *
     * @var string
     */
    public $name;

    /**
     * The recipe image.
     *
     * @var string
     */
    public $image;

    /**
     * Is the user admin.
     *
     * @var boolean
     */
    public $admin;

    /**
     * The list of ingredients.
     *
     * @var array
     */
    public $ingredients;

    /**
     * The recipe description.
     *
     * @var string
     */
    public $description;

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
     * The recipe total time.
     *
     * @var integer
     */
    public $totalTime;

    /**
     * The recipe tags.
     *
     * @var integer
     */
    public $tags;

    /**
     * Should diplay macros?
     *
     * @var boolean
     */
    public $displayMacros;

    /**
     * Create a new component instance.
     *
     * @param string name
     * @param string image
     * @param boolean admin
     * @param array ingredients
     * @param string description
     * @param integer protein
     * @param integer fat
     * @param integer carbohydrate
     * @param integer kcal
     * @param integer weightTotal
     * @param integer preparationTime
     * @param integer cookingTime
     * @param integer totalTime
     * @param array tags
     * @param boolean displayMacros
     * @return void
     */

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($name = NULL, $image = NULL, $admin = NULL, $ingredients = NULL, $description = NULL, $protein = NULL, $fat = NULL, $carbohydrate = NULL, $kcal = NULL, $weightTotal = NULL, $preparationTime = NULL, $cookingTime = NULL, $totalTime = NULL, $tags = NULL, $displayMacros = NULL)
    {
        $this->name = $name;
        $this->image = $image;
        $this->admin = $admin;
        $this->ingredients = $ingredients;
        $this->description = $description;
        $this->protein = $protein;
        $this->fat = $fat;
        $this->carbohydrate = $carbohydrate;
        $this->kcal = $kcal;
        $this->weightTotal = $weightTotal;
        $this->preparationTime = $preparationTime;
        $this->cookingTime = $cookingTime;
        $this->totalTime = $totalTime;
        $this->displayMacros = $displayMacros;
        $this->tags = $tags;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.recipe.recipe-preview');
    }
}
