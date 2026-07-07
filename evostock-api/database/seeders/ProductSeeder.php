<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categoryIds = Category::query()->pluck('id');

        Product::factory()
            ->count(20)
            ->create()
            ->each(fn (Product $product) => $product->categories()->attach(
                $categoryIds->random(random_int(1, 2))
            ));

        Product::factory()
            ->lowStock()
            ->count(5)
            ->create()
            ->each(fn (Product $product) => $product->categories()->attach(
                $categoryIds->random(random_int(1, 2))
            ));

        Product::factory()
            ->inactive()
            ->count(3)
            ->create()
            ->each(fn (Product $product) => $product->categories()->attach(
                $categoryIds->random(1)
            ));
    }
}
