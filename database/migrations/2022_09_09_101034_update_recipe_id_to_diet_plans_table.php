<?php

use App\Models\Recipe;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
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
        Schema::table('diet_plans', function (Blueprint $table) {
            $table->dropColumn('recipe_id');
        });

        Schema::table('diet_plans', function (Blueprint $table) {
            $table->after('modifier', function($table) {
                $table->foreignId('recipe_id')->constrained();
            });
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('diet_plans', function (Blueprint $table) {
            $table->dropColumn('recipe_id');
        });
        
        Schema::table('diet_plans', function (Blueprint $table) {
            $table->foreignIdFor(Recipe::class);
        });
    }
};