<?php

namespace App\Repositories\Interfaces;

use App\Models\Unit;
use Illuminate\Support\Collection;

interface UnitRepositoryInterface
{
    public function get(int $id) : Unit;
    public function getBySymbolOrName(string $symbol): Unit;
    public function getAll() : Collection;
}
