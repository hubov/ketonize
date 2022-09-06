<?php

use App\Models\Ingredient;
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
        Schema::table('ingredients', function (Blueprint $table) {            
            $table->after('name', function($table) {
                $table->decimal('protein_new', 4, 2)->nullable();
                $table->decimal('fat_new', 4, 2)->nullable();
                $table->decimal('carbohydrate_new', 4, 2)->nullable();
            });
        });

        foreach (Ingredient::all() as $ingredient)
        {
            $ingredient->protein_new = $ingredient->protein / 10;
            $ingredient->fat_new = $ingredient->fat / 10;
            $ingredient->carbohydrate_new = $ingredient->carbohydrate / 10;
            $ingredient->save();
        }

        Schema::table('ingredients', function (Blueprint $table) {
            $table->dropColumn('protein');
            $table->dropColumn('fat');
            $table->dropColumn('carbohydrate');

            $table->renameColumn('protein_new', 'protein')->change();
            $table->renameColumn('fat_new', 'fat')->change();
            $table->renameColumn('carbohydrate_new', 'carbohydrate')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ingredients', function (Blueprint $table) {
            $table->after('name', function($table) {
                $table->integer('protein_new')->nullable();
                $table->integer('fat_new')->nullable();
                $table->integer('carbohydrate_new')->nullable();
            });
        });

        foreach (Ingredient::all() as $ingredient)
        {
            $ingredient->protein_new = $ingredient->protein * 10;
            $ingredient->fat_new = $ingredient->fat * 10;
            $ingredient->carbohydrate_new = $ingredient->carbohydrate * 10;
            $ingredient->save();
        }

        Schema::table('ingredients', function (Blueprint $table) {
            $table->dropColumn('protein');
            $table->dropColumn('fat');
            $table->dropColumn('carbohydrate');

            $table->renameColumn('protein_new', 'protein')->change();
            $table->renameColumn('fat_new', 'fat')->change();
            $table->renameColumn('carbohydrate_new', 'carbohydrate')->change();

            $table->integer('protein')->nullable(false)->change();
            $table->integer('fat')->nullable(false)->change();
            $table->integer('carbohydrate')->nullable(false)->change();
        });
    }
};
