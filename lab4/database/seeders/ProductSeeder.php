<?php

namespace Database\Seeders;

use App\Models\Product;
use Faker\Generator;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /** seed варіанта №9 (збігається з config/catalog.php лабораторної №2). */
    private const SEED = 2109;

    private const COUNT = 12;

    public function run(): void
    {
        // Категорії мають існувати раніше за продукти.
        $this->call(CategorySeeder::class);

        $faker = app(Generator::class);
        $faker->unique(true);
        $faker->seed(self::SEED);

        for ($i = 0; $i < self::COUNT; $i++) {
            $product = Product::factory()->make();

            // Ідемпотентність: наявний продукт не дублюємо й не переписуємо змін.
            if (Product::query()->where('slug', $product->slug)->exists()) {
                continue;
            }

            $product->save();
        }
    }
}
