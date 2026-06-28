<?php

namespace Database\Seeders;

use App\Models\School;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(SchoolSeeder::class);
        $this->call(AcademicSeeder::class);

        $school = School::orderBy('name')->first();

        if (! $school) {
            $this->command->error('No schools were found. Add your schools to storage/app/schools.csv, then run php artisan db:seed again.');

            return;
        }

        User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'System Administrator',
                'password' => bcrypt('admin123'),
                'role' => 'admin',
                'role_status' => 'approved',
                'ward' => null,
                'school_id' => null,
            ]
        );

        School::query()
            ->select('ward')
            ->distinct()
            ->orderBy('ward')
            ->get()
            ->each(function (School $wardSchool) {
                User::updateOrCreate(
                    ['email' => 'weo-'.Str::slug($wardSchool->ward).'@example.com'],
                    [
                        'name' => $wardSchool->ward.' WEO',
                        'password' => bcrypt('weo123'),
                        'role' => 'weo',
                        'role_status' => 'approved',
                        'ward' => $wardSchool->ward,
                        'school_id' => null,
                    ]
                );
            });

        School::query()
            ->orderBy('name')
            ->get()
            ->each(function (School $school) {
                User::updateOrCreate(
                    ['email' => 'headteacher-'.Str::slug($school->name).'@example.com'],
                    [
                        'name' => $school->name.' Head Teacher',
                        'password' => bcrypt('head123'),
                        'role' => 'headmaster',
                        'role_status' => 'approved',
                        'ward' => null,
                        'school_id' => $school->id,
                    ]
                );
            });

        User::updateOrCreate(
            ['email' => 'weo@example.com'],
            [
                'name' => $school->ward.' WEO',
                'password' => bcrypt('weo123'),
                'role' => 'weo',
                'role_status' => 'approved',
                'ward' => $school->ward,
                'school_id' => null,
            ]
        );

        User::updateOrCreate(
            ['email' => 'headteacher@example.com'],
            [
                'name' => $school->name.' Head Teacher',
                'password' => bcrypt('head123'),
                'role' => 'headmaster',
                'role_status' => 'approved',
                'ward' => null,
                'school_id' => $school->id,
            ]
        );
    }
}
