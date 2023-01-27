<?php

namespace App\Services;

use App\Repositories\Interfaces\IngredientRepositoryInterface;
use App\Services\Interfaces\IngredientSearchInterface;

class IngredientSearchService implements IngredientSearchInterface
{
    protected $ingredientRepository;
    protected $query;
    protected $limit;

    public function __construct(IngredientRepositoryInterface $ingredientRepository)
    {
        $this->ingredientRepository = $ingredientRepository;

        return $this;
    }

    public function limit(int $limit): self
    {
        $this->limit = $limit;

        return $this;
    }

    public function query(string $query): self
    {
        $this->query = $query;

        return $this;
    }

    protected function getIngredients()
    {
        return $this->ingredientRepository->getByNameLimited('%'.$this->query.'%', $this->limit);
    }

    public function return() : array
    {
        $result = [];
        $ingredients = $this->getIngredients();

        if (count($ingredients) > 0) {
            $i = 0;
            foreach ($ingredients as $ingredient) {
                $result[
                    $this->getOrderByLevenshtein($ingredient->name, $i)
                ] = $ingredient;

                $i++;
            }
        }

        ksort($result);

        return array_values($result);
    }

    protected function getOrderByLevenshtein($name, $order)
    {
        return levenshtein($this->query, $name)
            *100
            +$order;
    }
}
