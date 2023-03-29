<?php

use App\Models\CustomIngredient;
use App\Models\RecipeIdea;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('custom_ingredient_recipe_idea', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(CustomIngredient::class)
                ->constrained()
                ->onDelete('cascade');
            $table->foreignIdFor(RecipeIdea::class)
                ->constrained()
                ->onDelete('cascade');
            $table->integer('amount');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('custom_ingredient_recipe_idea');
    }
};
