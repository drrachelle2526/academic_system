<?php

namespace Database\Seeders;

use App\Models\School;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class SchoolSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Target the file path location safely
        $filePath = storage_path('app/schools.csv');

        // 2. Check if the file physically exists before opening
        if (!file_exists($filePath)) {
            $this->command->error("CSV File not found at: {$filePath}");
            return;
        }

        // 3. Open and parse the CSV file stream rows
        if (($handle = fopen($filePath, 'r')) !== false) {
            // Skip the header row column labels (id, name, ward)
            fgetcsv($handle, 1000, ',');

            // Clear any existing residual items to avoid duplications
            School::truncate();

            // Loop through each entry row
            while (($data = fgetcsv($handle, 1000, ',')) !== false) {
                // Ensure the row has the required columns
                if (isset($data[1]) && isset($data[2])) {
                    School::create([
                        'name' => trim($data[1]), // Grabs name column
                        'ward' => trim($data[2]), // Grabs ward column
                    ]);
                }
            }
            fclose($handle);
            $this->command->info("Successfully seeded primary schools into database!");
        }
    }
}