<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_returns_the_dashboard_summary(): void
    {
        Category::factory()->count(2)->create();
        Product::factory()->count(3)->create(['is_active' => true, 'stock' => 20]);
        Product::factory()->inactive()->create(['stock' => 20]);
        Product::factory()->lowStock()->create(['is_active' => true]);

        Sanctum::actingAs(User::factory()->create());

        $response = $this->getJson('/api/v1/dashboard')->assertOk();

        $response->assertJsonPath('data.total_products', 5)
            ->assertJsonPath('data.total_categories', 2)
            ->assertJsonPath('data.active_products', 4)
            ->assertJsonPath('data.inactive_products', 1)
            ->assertJsonPath('data.low_stock_products', 1)
            ->assertJsonCount(5, 'data.latest_products');
    }
}
