<?php

namespace App\Repositories\Interfaces;

use App\Models\Diet;

interface DietRepositoryInterface
{
    public function get(int $id) : Diet;
}
