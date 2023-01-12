<?php

namespace App\Repositories;

use App\Models\User;
use App\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Support\Collection;

class UserRepository implements UserRepositoryInterface
{
    public function get(int $id) : User
    {
        return User::find($id);
    }

    public function getAllActive(): Collection
    {
        return User::has('userDiet')->get();
    }
}
