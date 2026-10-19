<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CatalogTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
    }

    public function test_seed_creates_four_categories_and_twelve_products(): void
    {
        $this->assertSame(4, Category::count());
        $this->assertSame(12, Product::count());
    }

    public function test_repeated_seed_is_idempotent(): void
    {
        $slugs = Product::orderBy('id')->pluck('slug')->all();

        $this->seed(DatabaseSeeder::class);

        $this->assertSame(4, Category::count());
        $this->assertSame(12, Product::count());
        $this->assertSame($slugs, Product::orderBy('id')->pluck('slug')->all());
    }

    public function test_home_page_shows_three_featured_products(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertSee('Tech Showcase')
            ->assertSee('Пригарін Богдан')
            ->assertSee('Лабораторна робота №3');
    }

    public function test_catalog_index_shows_first_page(): void
    {
        $this->get('/catalog')->assertOk()->assertSee('12 моделей');
    }

    public function test_catalog_search_with_no_matches_returns_200_and_message(): void
    {
        $this->get('/catalog?q=absent-lab3-product')
            ->assertOk()
            ->assertSee('Нічого не знайдено')
            ->assertSee('Скинути фільтри');
    }

    public function test_catalog_show_returns_existing_product(): void
    {
        $product = Product::orderBy('id')->first();

        $this->get('/catalog/'.$product->slug)
            ->assertOk()
            ->assertSee($product->title)
            ->assertSee('data-action="request"', false);
    }

    public function test_unknown_slug_returns_styled_html_404(): void
    {
        $this->get('/catalog/missing-item-slug-test')
            ->assertNotFound()
            ->assertSee('Error / route_not_found');
    }

    public function test_invalid_html_filter_redirects_back_with_errors(): void
    {
        $this->get('/catalog?sort=invalid')
            ->assertRedirect(route('catalog.index'))
            ->assertSessionHasErrors('sort');
    }

    public function test_api_list_returns_nine_of_twelve(): void
    {
        $this->getJson('/api/catalog')
            ->assertOk()
            ->assertJsonCount(9, 'data')
            ->assertJsonPath('meta.total', 12)
            ->assertJsonStructure(['data', 'links', 'meta']);
    }

    public function test_api_show_returns_product(): void
    {
        $product = Product::orderBy('id')->first();

        $this->getJson('/api/catalog/'.$product->slug)
            ->assertOk()
            ->assertJsonPath('data.slug', $product->slug)
            ->assertJsonPath('data.category', $product->category->name);
    }

    public function test_api_price_sort_is_ascending(): void
    {
        $prices = collect(
            $this->getJson('/api/catalog?sort=price_asc&per_page=12')->assertOk()->json('data')
        )->pluck('price')->map(fn ($p) => (float) $p)->all();

        $sorted = $prices;
        sort($sorted);
        $this->assertSame($sorted, $prices);
    }

    public function test_api_validation_errors_return_422(): void
    {
        $this->getJson('/api/catalog?sort=invalid')->assertStatus(422)->assertJsonValidationErrors('sort');
        $this->getJson('/api/catalog?page=0')->assertStatus(422)->assertJsonValidationErrors('page');
        $this->getJson('/api/catalog?per_page=10000')->assertStatus(422)->assertJsonValidationErrors('per_page');
        $this->getJson('/api/catalog?category=Unknown')->assertStatus(422)->assertJsonValidationErrors('category');
    }

    public function test_api_404_is_json(): void
    {
        $this->getJson('/api/catalog/missing-product')->assertNotFound()->assertJsonStructure(['message']);
        $this->getJson('/api/missing/page')->assertNotFound()->assertJsonStructure(['message']);
    }
}
