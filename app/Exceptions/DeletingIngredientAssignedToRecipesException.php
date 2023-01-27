<?php

namespace App\Exceptions;

use Exception;
use Throwable;

class DeletingIngredientAssignedToRecipesException extends Exception
{
    protected $recipeList;

    public function  __construct(array $recipeList, string $message = "", int $code = 0, ?Throwable $previous = null)
    {
        $this->recipeList = $recipeList;

        parent::__construct($message, $code, $previous);
    }

    public function getRecipeList()
    {
        return $this->recipeList;
    }
}
