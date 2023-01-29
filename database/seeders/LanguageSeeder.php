<?php

namespace Database\Seeders;

use App\Helpers\CPU\CreateFileLanguages;
use App\Models\Language;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;

class LanguageSeeder extends Seeder
{

    public function run()
    {
        DB::table(App::make(Language::class)->getTable())->truncate();
        /* language */
        Language::query()->create([
            'name' => 'عربي',
            'direction' => 'rtl',
            'code' => 'ar',
            'flag' => 'eg',
            'status' => 1,
            'default' => 1,
        ]);
        CreateFileLanguages::file('ar');
        Language::query()->create([
            'name' => 'english',
            'direction' => 'ltr',
            'code' => 'en',
            'flag' => 'en',
            'status' => 1,
            'default' => 0,
        ]);

    }
}
