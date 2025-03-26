<?php

namespace Database\Seeders;

use App\Models\Campaign;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CampaignSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $campaigns = json_decode(file_get_contents(database_path('seeders/data/campaigns.json')), true);

        foreach ($campaigns as $campaign) {
            // Ensure tags is properly handled as JSON
            if (isset($campaign['tags']) && is_array($campaign['tags'])) {
                $campaign['tags'] = json_encode($campaign['tags']);
            } else {
                $campaign['tags'] = null;
            }

            Campaign::upsert($campaign, ['id']);
        }
    }
}
