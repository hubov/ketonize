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
        Schema::table('recipes', function (Blueprint $table) {
            $table->after('kcal', function($table) {
                $table->tinyInteger('protein_ratio');
                $table->tinyInteger('fat_ratio');
                $table->tinyInteger('carbohydrate_ratio');
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
        Schema::table('recipes', function (Blueprint $table) {
            $table->dropColumn('protein_ratio');
            $table->dropColumn('fat_ratio');
            $table->dropColumn('carbohydrate_ratio');
        });
    }
};
