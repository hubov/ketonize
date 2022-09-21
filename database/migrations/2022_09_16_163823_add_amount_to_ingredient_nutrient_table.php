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
        Schema::table('ingredient_nutrient', function (Blueprint $table) {
            $table->after('nutrient_id', function($table) {
                $table->double('amount', 7, 2);
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
        Schema::table('ingredient_nutrient', function (Blueprint $table) {
            $table->dropColumn('amount');
        });
    }
};
