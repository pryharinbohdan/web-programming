<?php

namespace Tests\Feature;

use App\Services\CatalogService;
use Tests\TestCase;

class CatalogTest extends TestCase
{
    public function test_home_page_returns_successful_response(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('Tech Showcase');
        $response->assertSee('Пригарін Богдан');
    }

    public function test_catalog_index_returns_all_twelve_items(): void
    {
        $response = $this->get('/catalog');
        $response->assertStatus(200);
        $response->assertSee('12 моделей');
    }

    public function test_catalog_show_returns_existing_item(): void
    {
        /** @var CatalogService $service */
        $service = app(CatalogService::class);
        $item = $service->all()[0];

        $response = $this->get('/catalog/' . $item['slug']);
        $response->assertStatus(200);
        $response->assertSee($item['title']);
        $response->assertSee('data-action="request"', false);
    }

    public function test_catalog_show_returns_404_for_unknown_slug(): void
    {
        $response = $this->get('/catalog/missing-item-slug-test');
        $response->assertStatus(404);
        $response->assertSee('Error / route_not_found');
    }

    public function test_catalog_service_generates_all_required_keys(): void
    {
        /** @var CatalogService $service */
        $service = app(CatalogService::class);
        $items = $service->all();

        $this->assertCount(12, $items);

        $requiredKeys = [
            'id', 'slug', 'title', 'category', 'operation', 'price',
            'currency', 'short_description', 'description', 'image',
            'contact_name', 'contact_phone', 'contact_email', 'status',
            'published_at', 'attributes',
        ];

        foreach ($items as $item) {
            foreach ($requiredKeys as $key) {
                $this->assertArrayHasKey($key, $item, "Field '{$key}' must exist in item.");
            }
        }
    }
}
