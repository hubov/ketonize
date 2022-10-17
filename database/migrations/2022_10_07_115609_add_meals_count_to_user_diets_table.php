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
        Schema::table('user_diets', function (Blueprint $table) {
            $table->after('diet_id', function($table) {
                $table->integer('meals_count');
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
        Schema::table('user_diets', function (Blueprint $table) {
            $table->dropColumn('meals_count');
        });
    }
};
