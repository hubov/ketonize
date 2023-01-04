<?php

namespace App\Repositories;

use App\Models\Tag;
use App\Repositories\Interfaces\TagRepositoryInterface;
use Illuminate\Support\Collection;

class TagRepository implements TagRepositoryInterface
{
    public function get(int $id) : Tag
    {
        return Tag::find($id)->first();
    }

    public function getAll(): Collection
    {
        return Tag::all();
    }
}
