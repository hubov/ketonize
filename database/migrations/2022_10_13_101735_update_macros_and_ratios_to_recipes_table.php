<?php

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
        Schema::table('recipes', function (Blueprint $table) {
            $table->integer('protein')->default(0)->change();
            $table->integer('fat')->default(0)->change();
            $table->integer('carbohydrate')->default(0)->change();
            $table->integer('kcal')->default(0)->change();
            $table->integer('protein_ratio')->default(0)->change();
            $table->integer('fat_ratio')->default(0)->change();
            $table->integer('carbohydrate_ratio')->default(0)->change();
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
            DB::statement('ALTER TABLE `recipes`
	ALTER `protein` DROP DEFAULT,
	ALTER `fat` DROP DEFAULT,
	ALTER `carbohydrate` DROP DEFAULT,
	ALTER `kcal` DROP DEFAULT,
	ALTER `protein_ratio` DROP DEFAULT,
	ALTER `fat_ratio` DROP DEFAULT,
	ALTER `carbohydrate_ratio` DROP DEFAULT;');
        });
    }
};
