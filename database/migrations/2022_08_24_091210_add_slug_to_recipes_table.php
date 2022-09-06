<?php

use App\Models\Recipe;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

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
            $table->after('name', function($table) {
                $table->string('slug')->nullable();
            });
        });

        foreach (Recipe::all() as $recipe)
        {
            $recipe->slug = Str::of($recipe->name)->slug('-');
            $recipe->save();
        }

        Schema::table('recipes', function (Blueprint $table) {
            $table->string('slug')->nullable(false)->change();
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
            $table->dropColumn('slug');
        });
    }
};
