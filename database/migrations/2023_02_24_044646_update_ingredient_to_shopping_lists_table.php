<?php

use App\Models\Ingredient;
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
        Schema::table('shopping_lists', function (Blueprint $table) {
            $table->after('ingredient_id', function ($table) {
                $table->morphs('itemable');
            });
        });

        DB::table('shopping_lists')
            ->update([
                'itemable_id' => DB::raw('ingredient_id'),
                'itemable_type' => 'App\Models\Ingredient'
            ]);

        Schema::table('shopping_lists', function (Blueprint $table) {
            $table->dropColumn('ingredient_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('shopping_lists', function (Blueprint $table) {
            $table->after('user_id', function ($table) {
                $table->foreignIdFor(Ingredient::class);
            });
        });

        DB::table('shopping_lists')
            ->where('itemable_type', 'App\Models\Ingredient')
            ->update([
                'ingredient_id' => DB::raw('itemable_id')
            ]);
        DB::table('shopping_lists')
            ->where('itemable_type', '<>', 'App\Models\Ingredient')
            ->delete();

        Schema::table('shopping_lists', function (Blueprint $table) {
            $table->dropColumn(['itemable_id', 'itemable_type']);
        });
    }
};
