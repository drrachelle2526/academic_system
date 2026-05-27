<?php

namespace Database\Seeders;

use App\Models\School;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class SchoolSeeder extends Seeder
{
    public function run(): void
    {
        $filePath = storage_path('app/schools.csv');

        if (!file_exists($filePath)) {
            $this->command->error("❌ CSV file not found at: {$filePath}");
            return;
        }

        // Disable foreign key checks to safely truncate
        Schema::disableForeignKeyConstraints();
        School::truncate();
        Schema::enableForeignKeyConstraints();

        if (($handle = fopen($filePath, 'r')) !== false) {
            $count = 0;

            // Loop through every single row directly (No header skipping)
            while (($data = fgetcsv($handle, 1000, ',')) !== false) {
                // Index 0 = School Name, Index 1 = Ward Name
                if (isset($data[0]) && isset($data[1])) {
                    School::create([
                        'name' => trim($data[0]),
                        'ward' => trim($data[1]),
                    ]);
                    $count++;
                }
            }
            fclose($handle);
            
            $this->command->info("✅ SUCCESS: Successfully seeded {$count} primary schools into the database!");
        }
    }
}