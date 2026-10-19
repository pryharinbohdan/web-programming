<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Category>
 */
class CategoryFactory extends Factory
{
    protected $model = Category::class;

    /** Чотири категорії варіанта №9 (назва => slug). */
    public const CATEGORIES = [
        'Ноутбук' => 'noutbuk',
        'Монітор' => 'monitor',
        'Комплектуючі' => 'komplektuiuchi',
        'Настільний ПК' => 'nastilnyi-pk',
    ];

    public function definition(): array
    {
        $name = $this->faker->unique()->words(2, true);

        return ['name' => $name, 'slug' => \Illuminate\Support\Str::slug($name)];
    }

    /** Знаходить категорію за назвою або створює її через фабрику. */
    public static function resolve(string $name): Category
    {
        return Category::query()->where('name', $name)->first()
            ?? Category::factory()->create([
                'name' => $name,
                'slug' => self::CATEGORIES[$name],
            ]);
    }
}
