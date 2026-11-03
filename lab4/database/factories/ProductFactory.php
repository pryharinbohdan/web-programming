<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * Предметні набори назв, характеристик і цін варіанта №9 перенесено з config/catalog.php.
 * Faker викликається лише під час наповнення (сидери), але не під час HTTP-запитів.
 *
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{
    protected $model = Product::class;

    private const BRANDS = ['Acer', 'Lenovo', 'ASUS', 'Dell'];

    private const CONDITIONS = ['Новий', 'Вживаний', 'Відновлений'];

    private const WARRANTIES = ['3 місяці', '12 місяців', '24 місяці'];

    /** Назви товарів за категоріями. */
    private const TITLES = [
        'Ноутбук' => ['Ноутбук для навчання', 'Ультрабук для роботи', 'Ігровий ноутбук'],
        'Монітор' => ['Монітор для роботи', 'Ігровий монітор', 'Монітор для дизайну'],
        'Комплектуючі' => ['Ігрова відеокарта', 'Швидкий SSD-накопичувач', 'Модуль оперативної пам’яті'],
        'Настільний ПК' => ['Компактний комп’ютер', 'Системний блок для роботи', 'Ігровий системний блок'],
    ];

    /** Діапазони цін (межі в межах [1800; 98000] профілю варіанта). */
    private const PRICE_RANGES = [
        'Ноутбук' => [18000, 98000],
        'Монітор' => [4500, 32000],
        'Комплектуючі' => [1800, 45000],
        'Настільний ПК' => [15000, 85000],
    ];

    private const COMPONENT_SPECS = [
        'Ігрова відеокарта' => ['Тип' => 'Відеокарта', 'Інтерфейс' => 'PCI-e 4.0', 'Охолодження' => 'Активне'],
        'Швидкий SSD-накопичувач' => ['Тип' => 'SSD', 'Інтерфейс' => 'NVMe PCI-e 4.0', 'Охолодження' => 'Радіатор'],
        'Модуль оперативної пам’яті' => ['Тип' => 'DDR5', 'Інтерфейс' => 'DIMM', 'Охолодження' => 'Радіатор'],
    ];

    public function definition(): array
    {
        $categoryName = $this->faker->randomElement(array_keys(CategoryFactory::CATEGORIES));
        $baseTitle = $this->faker->randomElement(self::TITLES[$categoryName]);
        $brand = $this->faker->randomElement(self::BRANDS);
        // Унікальний номер у назві гарантує унікальний slug без часу й випадкових UUID.
        $title = $brand.' '.$baseTitle.' '.$this->faker->unique()->numberBetween(10, 99);
        [$min, $max] = self::PRICE_RANGES[$categoryName];

        return [
            'category_id' => fn () => CategoryFactory::resolve($categoryName)->id,
            'slug' => Str::slug($title),
            'title' => $title,
            'operation' => Product::OPERATIONS[0],
            'price' => (int) (round($this->faker->numberBetween($min, $max) / 100) * 100),
            'currency' => '₴',
            'short_description' => $this->faker->sentence(8),
            'description' => $this->faker->paragraph(3),
            'image' => 'images/catalog-atlas.png',
            'contact_name' => $this->faker->name(),
            'contact_phone' => '+380 '.$this->faker->numerify('## ### ## ##'),
            'contact_email' => $this->faker->safeEmail(),
            'status' => 'В наявності',
            // Фіксовані межі інтервалу: результат не залежить від поточного часу.
            'published_at' => $this->faker->dateTimeBetween('2026-01-01', '2026-03-31')->format('Y-m-d'),
            'attributes' => [
                'Стан' => $this->faker->randomElement(self::CONDITIONS),
                'Гарантія' => $this->faker->randomElement(self::WARRANTIES),
                'Бренд' => $brand,
            ] + $this->specsFor($categoryName, $baseTitle),
        ];
    }

    /** @return array<string, string> */
    private function specsFor(string $category, string $title): array
    {
        return match ($category) {
            'Ноутбук', 'Настільний ПК' => [
                'CPU' => 'Intel Core i'.$this->faker->randomElement([5, 7, 9]),
                'RAM' => $this->faker->randomElement([16, 32, 64]).' GB',
                'SSD' => $this->faker->randomElement(['512 GB', '1 TB', '2 TB']),
            ],
            'Монітор' => [
                'Діагональ' => $this->faker->randomElement(['24″', '27″', '32″', '34″']),
                'Частота' => $this->faker->randomElement(['144 Hz', '165 Hz', '240 Hz']),
                'Матриця' => $this->faker->randomElement(['IPS', 'OLED', 'VA']),
            ],
            default => self::COMPONENT_SPECS[$title],
        };
    }
}
