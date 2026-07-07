<?php

namespace Tests\Feature\Category;

use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class CategoryCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_lists_categories(): void
    {
        Category::factory()->count(3)->create();
        Sanctum::actingAs(User::factory()->create());

        $this->getJson('/api/v1/categories')
            ->assertOk()
            ->assertJsonCount(3, 'data');
    }

    public function test_it_creates_a_category(): void
    {
        Sanctum::actingAs(User::factory()->create());

        $response = $this->postJson('/api/v1/categories', ['name' => 'Electrónica']);

        $response->assertCreated()->assertJsonPath('data.name', 'Electrónica');
        $this->assertDatabaseHas('categories', ['name' => 'Electrónica']);
    }

    public function test_it_rejects_duplicate_category_names(): void
    {
        Category::factory()->create(['name' => 'Electrónica']);
        Sanctum::actingAs(User::factory()->create());

        $response = $this->postJson('/api/v1/categories', ['name' => 'Electrónica']);

        $response->assertUnprocessable()->assertJsonValidationErrors('name');
    }

    public function test_it_updates_a_category(): void
    {
        $category = Category::factory()->create(['name' => 'Hogar']);
        Sanctum::actingAs(User::factory()->create());

        $response = $this->putJson("/api/v1/categories/{$category->id}", ['name' => 'Hogar y Jardín']);

        $response->assertOk()->assertJsonPath('data.name', 'Hogar y Jardín');
    }

    public function test_it_deletes_a_category(): void
    {
        $category = Category::factory()->create();
        Sanctum::actingAs(User::factory()->create());

        $this->deleteJson("/api/v1/categories/{$category->id}")
            ->assertNoContent();

        $this->assertSoftDeleted('categories', ['id' => $category->id]);
    }
}
