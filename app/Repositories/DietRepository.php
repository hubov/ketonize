<?php

namespace App\Repositories;

use App\Models\Diet;
use App\Repositories\Interfaces\DietRepositoryInterface;

class DietRepository implements DietRepositoryInterface
{
    public function get(int $dietId) : Diet
    {
        return Diet::find($dietId);
    }
}
