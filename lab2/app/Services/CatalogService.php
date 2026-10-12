<?php

namespace App\Services;

use Faker\Factory;
use Illuminate\Support\Str;

class CatalogService
{
    protected array $items = [];

    public function __construct()
    {
        $this->generateCatalog();
    }

    /**
     * Генерація 12 стабільних оголошень за seed з конфігурації
     */
    protected function generateCatalog(): void
    {
        $variant = (int) config('catalog.variant', 9);
        $profile = config("catalog.profiles.{$variant}") ?? config('catalog.profiles.9');
        $count = (int) config('catalog.count', 12);

        $faker = Factory::create('uk_UA');
        $faker->seed((int) $profile['seed']);

        $slugs = [];
        $imageClasses = [
            'atlas-frame__image--first',
            'atlas-frame__image--second',
            'atlas-frame__image--third',
        ];

        for ($i = 1; $i <= $count; $i++) {
            $category = $faker->randomElement($profile['categories']);
            $brand = $faker->randomElement($profile['attributes']['Бренд'] ?? ['ASUS', 'Dell', 'Lenovo', 'Acer']);
            $baseTitle = $faker->randomElement($profile['titles'] ?? ['Ноутбук для навчання', 'Монітор для роботи']);
            $title = $brand . ' ' . $baseTitle . ' ' . $faker->numberBetween(10, 99);

            $slugCandidate = Str::slug($title);
            if (in_array($slugCandidate, $slugs, true)) {
                $slugCandidate .= '-' . $i;
            }
            $slugs[] = $slugCandidate;

            $attributes = [];
            foreach ($profile['attributes'] as $attrKey => $attrOptions) {
                $attributes[$attrKey] = $faker->randomElement($attrOptions);
            }

            $specs = match ($category) {
                'Ноутбук' => [
                    'CPU' => 'Intel Core i' . $faker->randomElement([5, 7, 9]),
                    'RAM' => $faker->randomElement([16, 32, 64]) . ' GB',
                    'SSD' => $faker->randomElement(['512 GB', '1 TB', '2 TB']),
                ],
                'Монітор' => [
                    'Діагональ' => $faker->randomElement(['24″', '27″', '32″', '34″']),
                    'Частота' => $faker->randomElement(['144 Hz', '165 Hz', '240 Hz']),
                    'Матриця' => $faker->randomElement(['IPS', 'OLED', 'VA']),
                ],
                default => [
                    'Тип' => 'Комплектуючі',
                    'Інтерфейс' => 'PCI-e 4.0 / USB-C',
                    'Охолодження' => 'Активне / радіатор',
                ],
            };

            $minPrice = $profile['price'][0] ?? 1800;
            $maxPrice = $profile['price'][1] ?? 98000;
            $price = (int) (round($faker->numberBetween($minPrice, $maxPrice) / 100) * 100);

            $this->items[] = [
                'id' => $i,
                'slug' => $slugCandidate,
                'title' => $title,
                'category' => $category,
                'brand' => $brand,
                'operation' => $profile['operation'] ?? 'Продаж',
                'price' => $price,
                'currency' => $profile['currency'] ?? '₴',
                'short_description' => $faker->sentence(8),
                'description' => $faker->paragraph(3),
                'image' => 'images/catalog-atlas.png',
                'contact_name' => $faker->name(),
                'contact_phone' => '+380 ' . $faker->numerify('## ### ## ##'),
                'contact_email' => $faker->safeEmail(),
                'status' => 'В наявності',
                'published_at' => $faker->dateTimeBetween('2026-01-01', '2026-03-31')->format('Y-m-d'),
                'attributes' => $attributes,
                'specs' => $specs,
                'featured' => $i <= 3,
                'badge' => $i === 1 ? 'Хіт продажу' : ($i === 2 ? 'Для ігор' : ($i === 3 ? 'Для дизайну' : null)),
                'badge_class' => $i === 2 ? 'tech-card__label--violet' : ($i === 3 ? 'tech-card__label--green' : ''),
                'image_class' => $imageClasses[($i - 1) % 3],
            ];
        }
    }

    public function featured(int $limit = 3): array
    {
        return array_slice($this->items, 0, $limit);
    }

    public function all(): array
    {
        return $this->items;
    }

    public function findBySlug(string $slug): ?array
    {
        foreach ($this->items as $item) {
            if ($item['slug'] === $slug) {
                return $item;
            }
        }

        return null;
    }
}
