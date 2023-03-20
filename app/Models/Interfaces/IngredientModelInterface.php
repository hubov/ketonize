<?php

namespace App\Models\Interfaces;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

interface IngredientModelInterface
{
    public function unit() : BelongsTo;
}
