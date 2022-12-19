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
        $oldDietPlans = DB::table('diet_plans')->select('*')->get();

        if (isset($oldDietPlans)) {
            $i = 1;
            foreach ($oldDietPlans as $oldPlan) {
                if (!isset($newIds[$oldPlan->user_id . $oldPlan->date_on])) {
                    $newIds[$oldPlan->user_id . $oldPlan->date_on] = $i;

                    $newDietPlans[$i] = [
                        'id' => NULL,
                        'user_id' => $oldPlan->user_id,
                        'date_on' => $oldPlan->date_on
                    ];

                    $i++;
                }

                $meals[] = [
                    'id' => NULL,
                    'diet_plan_id' => $newIds[$oldPlan->user_id . $oldPlan->date_on],
                    'recipe_id' => $oldPlan->recipe_id,
                    'meal' => $oldPlan->meal,
                    'modifier' => $oldPlan->modifier
                ];
            }

            if (isset($newDietPlans)) {
                DB::table('diet_plans_new')->insert($newDietPlans);
            }

            if (isset($meals)) {
                DB::table('meals')->insert($meals);
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('diet_plans_to_new_diet_plans_and_meals', function (Blueprint $table) {
            $newDietPlans = DB::table('diet_plans_new')->select('*')->get();
            $meals = DB::table('meals')->select('*')->get();

            foreach ($newDietPlans as $dietPlan) {
                $newIds[$dietPlan->id] = [
                    'user_id' => $dietPlan->user_id,
                    'date_on' => $dietPlan->date_on
                ];
            }

            foreach ($meals as $meal) {
                $oldDietPlans[] = [
                    'user_id' => $newIds[$meal->diet_plan_id]['user_id'],
                    'modifier' => $meal->modifier,
                    'recipe_id' => $meal->recipe_id,
                    'meal' => $meal->meal,
                    'date_on' => $newIds[$meal->diet_plan_id]['date_on']
                ];
            }

            DB::table('diet_plans')->insert($oldDietPlans);
        });
    }
};
