<?php

namespace App\Models;

use App\Models\Interfaces\RecipeModelInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RecipeIdea extends Model implements RecipeModelInterface
{
    use HasFactory;
}
