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
            $table->after('image', function($table) {
                $table->integer('preparation_time');
                $table->integer('cooking_time');
                $table->integer('total_time');
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
            $table->dropColumn('preparation_time');
            $table->dropColumn('cooking_time');
            $table->dropColumn('total_time');
        });
    }
};
