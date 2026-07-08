<?php

namespace Tests\Feature\Product;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ProductCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_creates_a_product_with_categories(): void
    {
        $categories = Category::factory()->count(2)->create();
        Sanctum::actingAs(User::factory()->create());

        $response = $this->postJson('/api/v1/products', [
            'name' => 'Laptop',
            'price' => 999.99,
            'stock' => 5,
            'entry_date' => '2026-01-15',
            'category_ids' => $categories->pluck('id')->all(),
        ]);

        $response->assertCreated()
            ->assertJsonPath('data.name', 'Laptop')
            ->assertJsonPath('data.entry_date', '2026-01-15')
            ->assertJsonCount(2, 'data.categories');
        $this->assertDatabaseHas('products', ['name' => 'Laptop', 'entry_date' => '2026-01-15']);
    }

    public function test_it_requires_an_entry_date_on_create(): void
    {
        Sanctum::actingAs(User::factory()->create());

        $this->postJson('/api/v1/products', [
            'name' => 'Sin fecha',
            'price' => 10,
            'stock' => 1,
        ])->assertStatus(422)->assertJsonValidationErrors('entry_date');
    }

    public function test_it_filters_products_by_category(): void
    {
        $category = Category::factory()->create();
        $matching = Product::factory()->create();
        $matching->categories()->attach($category);
        Product::factory()->create();

        Sanctum::actingAs(User::factory()->create());

        $this->getJson("/api/v1/products?category_id={$category->id}")
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.id', $matching->id);
    }

    public function test_it_filters_products_by_status(): void
    {
        Product::factory()->count(2)->create(['is_active' => true]);
        Product::factory()->inactive()->create();

        Sanctum::actingAs(User::factory()->create());

        $this->getJson('/api/v1/products?status=0')
            ->assertOk()
            ->assertJsonCount(1, 'data');
    }

    public function test_it_sorts_products_by_name(): void
    {
        Product::factory()->create(['name' => 'Zapato']);
        Product::factory()->create(['name' => 'Audífonos']);

        Sanctum::actingAs(User::factory()->create());

        $response = $this->getJson('/api/v1/products?sort_by=name&sort_dir=asc')->assertOk();

        $this->assertSame('Audífonos', $response->json('data.0.name'));
        $this->assertSame('Zapato', $response->json('data.1.name'));
    }

    public function test_it_sorts_products_by_entry_date(): void
    {
        Product::factory()->create(['name' => 'Antiguo', 'entry_date' => '2024-01-01']);
        Product::factory()->create(['name' => 'Reciente', 'entry_date' => '2026-06-01']);

        Sanctum::actingAs(User::factory()->create());

        $response = $this->getJson('/api/v1/products?sort_by=entry_date&sort_dir=desc')->assertOk();

        $this->assertSame('Reciente', $response->json('data.0.name'));
        $this->assertSame('Antiguo', $response->json('data.1.name'));
    }

    public function test_it_deletes_a_product(): void
    {
        $product = Product::factory()->create();
        Sanctum::actingAs(User::factory()->create());

        $this->deleteJson("/api/v1/products/{$product->id}")->assertNoContent();

        $this->assertSoftDeleted('products', ['id' => $product->id]);
    }
}
