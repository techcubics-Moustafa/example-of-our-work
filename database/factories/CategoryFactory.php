<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;

class CategoryFactory extends Factory
{
    protected $model = Category::class;

    public function definition(): array
    {
        $locales = locales();
        $categories = Category::query()->whereNull('parent_id')->pluck('id')->toArray();
        $data = [
            'ranking' => random_int(1, 150),
            'parent_id' => $this->faker->randomElement($categories),
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
