<?php

namespace Database\Factories;

use App\Models\Feature;
use Illuminate\Database\Eloquent\Factories\Factory;

class FeatureFactory extends Factory
{
    protected $model = Feature::class;

    public function definition(): array
    {
        $locales = locales();
        $data = [
            'ranking' => random_int(1, 150),
            'status' => $this->faker->randomElement([1]),
        ];
        foreach ($locales as $locale) {
            $data += [
                'name:' . $locale => $this->faker->word . '_' . $locale,
            ];
        }
        return $data;
    }
}
