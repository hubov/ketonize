<?php

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
        Schema::create('diet_meal_divisions', function (Blueprint $table) {
            $table->id();
            $table->integer('meals_count');
            $table->integer('meal_order');
            $table->foreignIdFor(Tag::class);
            $table->integer('kcal_share');
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
        Schema::dropIfExists('diet_meal_divisions');
    }
};
