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

    public function limit(int $limit)
    {
        $this->limit = $limit;

        return $this;
    }

    public function query(string $query)
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
                $result[levenshtein($this->query, $ingredient->name)*100+$i] = [
                    'id' => $ingredient->id,
                    'name' => $ingredient->name,
                    'unit' => $ingredient->unit->symbol,
                    'protein' => $ingredient->protein,
                    'fat' => $ingredient->fat,
                    'carbohydrate' => $ingredient->carbohydrate,
                    'kcal' => $ingredient->kcal
                ];

                $i++;
            }
        }

        return $result;
    }
}
