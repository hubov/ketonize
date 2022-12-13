<?php

use App\Models\DietMealDivision;
use App\Models\Tag;
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
        Schema::create('meal_energy_divisions', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(DietMealDivision::class);
            $table->foreignIdFor(Tag::class);
            $table->tinyInteger('meal_order');
            $table->tinyInteger('kcal_share');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('meal_energy_divisions');
    }
};
