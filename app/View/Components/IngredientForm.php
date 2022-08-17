<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\Database\Eloquent\Collection;

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
     * Create a new component instance.
     *
     * @param string name
     * @param integer protein
     * @param integer fat
     * @param integer carbohydrate
     * @param integer kcal
     * @param integer unit
     * @param \Illuminate\Database\Eloquent\Collection units
     * @return void
     */


    public function __construct($name = '', $protein = '', $fat = '', $carbohydrate = '', $kcal = '', $unit = '', $units = [])
    {
        $this->name = $name;
        $this->protein = $protein;
        $this->fat = $fat;
        $this->carbohydrate = $carbohydrate;
        $this->kcal = $kcal;
        $this->unit_id = $unit;
        $this->units = $units;
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
