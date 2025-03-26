<?php

namespace Database\Seeders;

use App\Models\Brand;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BrandSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $brands = json_decode(file_get_contents(database_path('seeders/data/brands.json')), true);

        foreach ($brands as $brand) {
            Brand::upsert($brand, ['id']);
        }
    }
}
