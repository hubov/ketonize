<?php

namespace App\View\Components;

use App\Models\ShoppingList;
use Illuminate\View\Component;

class ShoppingListRow extends Component
{
    public $element;
    public $scalablesCount;
    public $categoryId;

    public function __construct(ShoppingList $element = NULL, int $scalablesCount = NULL, int $categoryId = NULL)
    {
        $this->element = $element;
        $this->scalablesCount = $scalablesCount;
        $this->categoryId = $categoryId;
    }

    public function render()
    {
        return view('components.shopping-list-row');
    }
}
