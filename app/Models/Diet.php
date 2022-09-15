<?php

namespace App\Models;

use App\Model\UserDiet;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Diet extends Model
{
    use HasFactory;

    public function diet()
    {
        return $this->hasMany(UserDiet::class);
    }
}
