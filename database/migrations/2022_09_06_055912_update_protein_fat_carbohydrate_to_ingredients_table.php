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
        Schema::table('ingredients', function (Blueprint $table) {
            $table->decimal('protein', 5, 2)->change();
            $table->decimal('fat', 5, 2)->change();
            $table->decimal('carbohydrate', 5, 2)->change();
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
            $table->decimal('protein', 4, 2)->change();
            $table->decimal('fat', 4, 2)->change();
            $table->decimal('carbohydrate', 4, 2)->change();
        });
    }
};
