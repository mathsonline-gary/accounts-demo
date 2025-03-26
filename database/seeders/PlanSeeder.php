<?php

namespace Database\Seeders;

use App\Models\Plan;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $plans = json_decode(file_get_contents(database_path('seeders/data/plans.json')), true);
        $campaignPlans = json_decode(file_get_contents(database_path('seeders/data/campaign_plan.json')), true);

        foreach ($plans as $plan) {
            Plan::upsert($plan, ['id']);
        }

        foreach ($campaignPlans as $campaignPlan) {
            DB::table('campaign_plan')->upsert($campaignPlan, ['campaign_id', 'plan_id']);
        }
    }
}
