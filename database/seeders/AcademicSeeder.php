<?php

namespace Database\Seeders;

use App\Models\Exam;
use App\Models\School;
use App\Models\SchoolClass;
use App\Models\Subject;
use Illuminate\Database\Seeder;

class AcademicSeeder extends Seeder
{
    public function run(): void
    {
        collect([
            ['name' => 'Kiswahili', 'code' => 'KIS'],
            ['name' => 'English', 'code' => 'ENG'],
            ['name' => 'Mathematics', 'code' => 'MATH'],
            ['name' => 'Science and Technology', 'code' => 'SCI'],
            ['name' => 'Social Studies', 'code' => 'SST'],
            ['name' => 'Civic and Moral Education', 'code' => 'CME'],
            ['name' => 'Vocational Skills', 'code' => 'VOC'],
        ])->each(fn (array $subject) => Subject::updateOrCreate(
            ['name' => $subject['name']],
            ['code' => $subject['code']]
        ));

        School::query()->each(function (School $school) {
            collect(['Standard 1', 'Standard 2', 'Standard 3', 'Standard 4', 'Standard 5', 'Standard 6', 'Standard 7'])
                ->each(fn (string $className) => SchoolClass::firstOrCreate([
                    'school_id' => $school->id,
                    'name' => $className,
                    'stream' => 'main',
                ]));

            Exam::firstOrCreate([
                'school_id' => $school->id,
                'name' => 'Annual Examination',
                'term' => 'Term 2',
                'year' => now()->year,
            ]);
        });
    }
}
