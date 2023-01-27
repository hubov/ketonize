<?php

use App\Models\DietMealDivision;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_diets', function (Blueprint $table) {
            $table->after('diet_id', function($table) {
                $table->foreignIdFor(DietMealDivision::class)->nullable();
            });
        });

        DB::table('user_diets')->join('diet_meal_divisions', 'user_diets.meals_count', '=', 'diet_meal_divisions.meals_count')->update(['diet_meal_division_id' => DB::raw('diet_meal_divisions.id')]);

        Schema::table('user_diets', function (Blueprint $table) {
            $table->dropColumn('meals_count');
            $table->foreignId('diet_meal_division_id')->nullable(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_diets', function (Blueprint $table) {
            $table->after('diet_id', function($table) {
                $table->tinyInteger('meals_count')->nullable();
            });
        });

        DB::table('user_diets')->join('diet_meal_divisions', 'user_diets.diet_meal_division_id', '=', 'diet_meal_divisions.id')->update(['user_diets.meals_count' => DB::raw('diet_meal_divisions.meals_count')]);

        Schema::table('user_diets', function (Blueprint $table) {
            $table->dropColumn('diet_meal_division_id');
            $table->integer('meals_count')->nullable(false)->change();
        });
    }
};
