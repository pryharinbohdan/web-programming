<?php

namespace Database\Seeders;

use App\Models\Category;
use Database\Factories\CategoryFactory;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        foreach (CategoryFactory::CATEGORIES as $name => $slug) {
            // Ідемпотентність: наявну категорію не дублюємо й не переписуємо.
            if (Category::query()->where('slug', $slug)->exists()) {
                continue;
            }

            Category::factory()->create(['name' => $name, 'slug' => $slug]);
        }
    }
}
