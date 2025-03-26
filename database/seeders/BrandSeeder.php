<?php

namespace Database\Seeders;

use App\Models\Brand;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;
use RuntimeException;

class BrandSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jsonPath = database_path('seeders/data/brands.json');

        if (! file_exists($jsonPath)) {
            throw new RuntimeException("Brands seed file not found at: {$jsonPath}");
        }

        $jsonContent = file_get_contents($jsonPath);
        if ($jsonContent === false) {
            throw new RuntimeException('Failed to read brands seed file');
        }

        $data = json_decode($jsonContent, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new RuntimeException('Invalid JSON in brands seed file: '.json_last_error_msg());
        }

        if (! isset($data['brands']) || ! is_array($data['brands'])) {
            throw new RuntimeException("Invalid brands seed file structure: 'brands' array not found");
        }

        foreach ($data['brands'] as $index => $brandData) {
            try {
                $brand = Brand::create($brandData);
                $this->command->info("Created brand: {$brand->name}");
            } catch (\Exception $e) {
                Log::error("Failed to create brand at index {$index}", [
                    'error' => $e->getMessage(),
                    'brand_data' => $brandData,
                ]);
                throw new RuntimeException(
                    "Failed to create brand at index {$index}: ".$e->getMessage()
                );
            }
        }
    }
}
