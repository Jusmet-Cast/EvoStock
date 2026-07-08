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
        $categories = [
            ['name' => 'Línea Blanca', 'description' => 'Neveras, lavadoras, secadoras y congeladores.'],
            ['name' => 'Electrodomésticos', 'description' => 'Pequeños electrodomésticos para cocina y hogar.'],
            ['name' => 'Muebles', 'description' => 'Mobiliario para sala, comedor y dormitorio.'],
            ['name' => 'Cocina', 'description' => 'Ollas, utensilios y accesorios de cocina.'],
            ['name' => 'Juguetería', 'description' => 'Juguetes y artículos infantiles.'],
            ['name' => 'Textiles y Blancos', 'description' => 'Sábanas, cobijas, toallas y cortinas.'],
            ['name' => 'Electrónica', 'description' => 'Televisores, audio y accesorios tecnológicos.'],
        ];

        foreach ($categories as $category) {
            Category::factory()->create($category);
        }

        Category::factory()->inactive()->create([
            'name' => 'Descontinuados',
            'description' => 'Productos fuera de línea, mantenidos solo por historial.',
        ]);
    }
}
