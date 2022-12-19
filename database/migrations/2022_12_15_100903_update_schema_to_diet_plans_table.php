<?php

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
        Schema::rename('diet_plans', 'diet_plans_old');
        Schema::rename('diet_plans_new', 'diet_plans');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::rename('diet_plans', 'diet_plans_new');
        Schema::rename('diet_plans_old', 'diet_plans');
    }
};
