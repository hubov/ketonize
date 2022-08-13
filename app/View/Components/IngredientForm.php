<?php

namespace App\View\Components;

use Illuminate\View\Component;

class IngredientForm extends Component
{
    /**
     * The ingredient name.
     *
     * @var string
     */
    public $name;

    /**
     * The ingredient's protein inhalt.
     *
     * @var integer
     */
    public $protein;

    /**
     * The ingredient's fat inhalt.
     *
     * @var integer
     */
    public $fat;

    /**
     * The ingredient's carbohydrate inhalt.
     *
     * @var integer
     */
    public $carbohydrate;

    /**
     * The ingredient's calories inhalt.
     *
     * @var integer
     */
    public $kcal;

    /**
     * Create a new component instance.
     *
     * @param string name
     * @param integer protein
     * @param integer fat
     * @param integer carbohydrate
     * @param integer kcal
     * @return void
     */


    public function __construct($name = '', $protein = '', $fat = '', $carbohydrate = '', $kcal = '')
    {
        $this->name = $name;
        $this->protein = $protein;
        $this->fat = $fat;
        $this->carbohydrate = $carbohydrate;
        $this->kcal = $kcal;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.ingredient-form');
    }
}
