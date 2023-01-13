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
        DB::table('meals')
            ->whereNotIn('diet_plan_id', DB::table('diet_plans')->pluck('id'))
            ->delete();

        Schema::table('meals', function (Blueprint $table) {
            $table->foreign('diet_plan_id')
                    ->references('id')
                    ->on('diet_plans')
                    ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('meals', function (Blueprint $table) {
            $table->dropForeign(['diet_plan_id']);
        });
    }
};
