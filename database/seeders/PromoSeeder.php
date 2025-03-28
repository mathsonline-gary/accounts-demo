<?php

namespace Database\Seeders;

use App\Models\Promo;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PromoSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $promos = json_decode(file_get_contents(database_path('seeders/data/promos.json')), true);

        foreach ($promos as $promo) {
            Promo::upsert($promo, ['id']);
        }
    }
}
