<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\Database\Eloquent\Collection;

class IngredientForm extends Component
{
    /**
     * The form method.
     *
     * @var string
     */
    public $method;

    /**
     * The ingredient name.
     *
     * @var string
     */
    public $name;

    /**
     * The ingredient's category.
     *
     * @var integer
     */
    public $category;

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
     * The ingredient's measure unit.
     *
     * @var integer
     */
    public $unit_id;

    /**
     * The list of measure unit.
     *
     * @var \Illuminate\Database\Eloquent\Collection
     */
    public $units;

    /**
     * The list of ingredient categories.
     *
     * @var \Illuminate\Database\Eloquent\Collection
     */
    public $categories;

    /**
     * Create a new component instance.
     *
     * @param string name
     * @param integer category
     * @param integer protein
     * @param integer fat
     * @param integer carbohydrate
     * @param integer kcal
     * @param integer unit
     * @param \Illuminate\Database\Eloquent\Collection units
     * @param \Illuminate\Database\Eloquent\Collection categories
     * @return void
     */


    public function __construct($method = 'POST', $name = '', $category = 0, $protein = '', $fat = '', $carbohydrate = '', $kcal = '', $unit = '', $units = [], $categories = [])
    {
        $this->method = $method;
        $this->name = $name;
        $this->category = $category;
        $this->protein = $protein;
        $this->fat = $fat;
        $this->carbohydrate = $carbohydrate;
        $this->kcal = $kcal;
        $this->unit_id = $unit;
        $this->units = $units;
        $this->categories = $categories;
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
