<?php

namespace App\Repositories;

use App\Models\Unit;
use App\Repositories\Interfaces\UnitRepositoryInterface;
use Illuminate\Support\Collection;

class UnitRepository implements UnitRepositoryInterface
{
    public function get(int $id) : Unit
    {
        return Unit::find($id);
    }

    public function getAll(): Collection
    {
        return Unit::all();
    }
}
