<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Feature;
use App\Models\ReportComment;
use App\Models\Service;
use App\Models\Special;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            LanguageSeeder::class,
            CountrySeeder::class,
            RolePermissionSeeder::class,
        ]);
        Category::factory(5)->create();
        Category::factory(10)->create();
        Special::factory(3)->create();
        Service::factory(3)->create();
        Feature::factory(10)->create();
        ReportComment::factory(10)->create();
        $this->call([
            UserSeeder::class,
        ]);
    }
}
