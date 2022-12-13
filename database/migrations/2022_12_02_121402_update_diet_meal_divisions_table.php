<?php

use App\Models\DietMealDivision;
use App\Models\Tag;
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
        $oldData = DietMealDivision::select('meals_count', 'meal_order', 'tag_id', 'kcal_share')->get();

        $newData = [];
        $newEnergyMealDivs = [];

        foreach ($oldData as $old) {
            $newData[$old['meals_count']] = [
                'meals_count' => $old['meals_count']
            ];
            $newEnergyMealDivs[] = [
                'meals_count' => $old['meals_count'],
                'tag_id' => $old['tag_id'],
                'meal_order' => $old['meal_order'],
                'kcal_share' => $old['kcal_share']
            ];
        }

        Schema::create('diet_meal_divisions_new', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('meals_count');
            $table->timestamps();
        });

        DB::table('diet_meal_divisions_new')->insert($newData);
        $newIdsList = DB::table('diet_meal_divisions_new')->select('id', 'meals_count')->get();

        $newIds = [];

        foreach ($newIdsList as $id) {
            $newIds[$id->meals_count] = $id->id;
        }

        foreach ($newEnergyMealDivs as $index => $newMeal) {
            $newEnergyMealDivs[$index]['diet_meal_division_id'] = $newIds[$newMeal['meals_count']];
            unset($newEnergyMealDivs[$index]['meals_count']);
        }

        DB::table('meal_energy_divisions')->insert($newEnergyMealDivs);

        Schema::dropIfExists('diet_meal_divisions');
        Schema::rename('diet_meal_divisions_new', 'diet_meal_divisions');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
//        $oldData = DB::table('meal_energy_divisions')->select('meals_count', 'tag_id', 'meal_order', 'kcal_share')->join('diet_meal_divisions', 'meal_energy_divisions.diet_meal_division_id', '=', 'diet_meal_divisions.id')->get()->toArray();
        $oldData = DB::connection()->getPdo()->query('SELECT meals_count, tag_id, meal_order, kcal_share FROM meal_energy_divisions JOIN diet_meal_divisions ON meal_energy_divisions.diet_meal_division_id = diet_meal_divisions.id')->fetchAll(PDO::FETCH_ASSOC);

        Schema::create('diet_meal_divisions_new', function (Blueprint $table) {
            $table->id();
            $table->integer('meals_count');
            $table->integer('meal_order');
            $table->foreignIdFor(Tag::class);
            $table->integer('kcal_share');
            $table->timestamps();
        });

        DB::table('diet_meal_divisions_new')->insert($oldData);

        Schema::dropIfExists('diet_meal_divisions');
        Schema::rename('diet_meal_divisions_new', 'diet_meal_divisions');
        DB::table('meal_energy_divisions')->truncate();
    }
};
