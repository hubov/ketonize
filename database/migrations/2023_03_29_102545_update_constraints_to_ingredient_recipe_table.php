<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::table('ingredient_recipe')
            ->whereNotIn('ingredient_id', DB::table('ingredients')->pluck('id'))
            ->delete();
        DB::table('ingredient_recipe')
            ->whereNotIn('recipe_id', DB::table('recipes')->pluck('id'))
            ->delete();

        Schema::table('ingredient_recipe', function (Blueprint $table) {
            $table->foreign('ingredient_id')
                ->references('id')
                ->on('ingredients')
                ->onDelete('cascade');
            $table->foreign('recipe_id')
                ->references('id')
                ->on('recipes')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ingredient_recipe', function (Blueprint $table) {
            $table->dropForeign(['ingredient_id']);
            $table->dropForeign(['recipe_id']);
        });
    }
};
