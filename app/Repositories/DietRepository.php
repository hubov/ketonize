<?php

namespace App\Repositories;

use App\Models\Diet;
use App\Repositories\Interfaces\DietRepositoryInterface;

class DietRepository implements DietRepositoryInterface
{
    public function get(int $id) : Diet
    {
        return Diet::find($id)->first();
    }
}
