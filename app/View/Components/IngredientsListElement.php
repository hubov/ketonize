<?php

namespace App\View\Components;

use Illuminate\View\Component;

class IngredientsListElement extends Component
{
    /**
     * The ingredient's name.
     *
     * @var string
     */
    public $name;

    /**
     * The ingredient's scalable count.
     *
     * @var integer
     */
    public $scalableCount;

    /**
     * The ingredient's amount.
     *
     * @var integer
     */
    public $amount;

    /**
     * The ingredient's unit symbol.
     *
     * @var string
     */
    public $symbol;

    /**
     * Create a new component instance.
     *
     * @param string name
     * @param integer scalableCount
     * @param integer amount
     * @param integer symbol
     * @return void
     */

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($name = NULL, $scalableCount = NULL, $amount = NULL, $symbol = NULL)
    {
        $this->name = $name;
        $this->scalableCount = $scalableCount;
        $this->amount = $amount;
        $this->symbol = $symbol;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.ingredients-list-element');
    }
}
