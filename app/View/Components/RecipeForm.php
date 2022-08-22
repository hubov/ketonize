<?php

namespace App\View\Components;

use Illuminate\View\Component;

class RecipeForm extends Component
{
    /**
     * The ingredient name.
     *
     * @var string
     */
    public $name;

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
     * @param \Illuminate\Database\Eloquent\Collection units
     * @return void
     */
    public function __construct($name = '', $units = '')
    {
        $this->name = $name;
        $this->units = $units;
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
