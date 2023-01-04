<?php

namespace App\Repositories\Interfaces;

use App\Models\Tag;
use Illuminate\Support\Collection;

interface TagRepositoryInterface
{
    public function get(int $id) : Tag;
    public function getAll() : Collection;
}
