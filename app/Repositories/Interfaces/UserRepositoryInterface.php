<?php

namespace App\Repositories\Interfaces;

use App\Models\User;
use Illuminate\Support\Collection;

interface UserRepositoryInterface
{
    public function get(int $id) : User;
    public function getWithProfile(int $id) : User;
    public function getAllActive() : Collection;
}
