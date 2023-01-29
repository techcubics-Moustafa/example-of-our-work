<?php

namespace Database\Factories;

use App\Models\Feature;
use App\Models\ReportComment;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReportCommentFactory extends Factory
{
    protected $model = ReportComment::class;

    public function definition(): array
    {
        $locales = locales();
        $data = [
            'ranking' => random_int(1, 150),
            'status' => $this->faker->randomElement([1]),
        ];
        foreach ($locales as $locale) {
            $data += [
                'title:' . $locale => $this->faker->word . '_' . $locale,
            ];
        }
        return $data;
    }
}
