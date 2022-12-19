<?php

namespace App\Models;

use DateTime;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'height', 'weight', 'target_weight', 'gender', 'diet_target', 'basic_activity', 'sport_activity', 'birthday'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function age()
    {
        $birthday = new DateTime($this->birthday);
        $today = new DateTime(date("Y-m-d"));

        return $today->diff($birthday)->y;
    }
}
