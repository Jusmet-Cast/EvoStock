<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        collect(['Electrónica', 'Hogar', 'Ropa', 'Juguetes', 'Deportes'])
            ->each(fn (string $name) => Category::factory()->create(['name' => $name]));

        Category::factory()->inactive()->create(['name' => 'Descontinuados']);
    }
}
