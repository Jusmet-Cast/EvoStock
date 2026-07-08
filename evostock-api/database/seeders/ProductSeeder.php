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
        $products = [
            // Línea Blanca
            ['name' => 'Nevera No Frost 15 Pies', 'description' => 'Refrigeradora No Frost de 15 pies cúbicos, acabado inoxidable.', 'price' => 899.99, 'stock' => 12, 'categories' => ['Línea Blanca']],
            ['name' => 'Nevera Side by Side 22 Pies', 'description' => 'Refrigeradora de dos puertas con dispensador de agua y hielo.', 'price' => 1299.00, 'stock' => 3, 'categories' => ['Línea Blanca']],
            ['name' => 'Lavadora Automática 18kg', 'description' => 'Lavadora de carga superior con 10 programas de lavado.', 'price' => 549.90, 'stock' => 8, 'categories' => ['Línea Blanca']],
            ['name' => 'Secadora a Gas 16kg', 'description' => 'Secadora a gas con sensor de humedad.', 'price' => 479.50, 'stock' => 5, 'categories' => ['Línea Blanca']],
            ['name' => 'Congelador Horizontal 7 Pies', 'description' => 'Congelador horizontal ideal para negocios y hogares.', 'price' => 399.00, 'stock' => 20, 'categories' => ['Línea Blanca']],

            // Electrodomésticos
            ['name' => 'Licuadora Oster 3 Velocidades', 'description' => 'Licuadora de vaso de vidrio, cuchillas de acero inoxidable.', 'price' => 39.99, 'stock' => 45, 'categories' => ['Electrodomésticos']],
            ['name' => 'Microondas Digital 1.1 Pies', 'description' => 'Microondas digital con panel táctil y 10 niveles de potencia.', 'price' => 89.90, 'stock' => 30, 'categories' => ['Electrodomésticos']],
            ['name' => 'Freidora de Aire 5.5L', 'description' => 'Freidora de aire caliente de capacidad familiar.', 'price' => 69.99, 'stock' => 18, 'categories' => ['Electrodomésticos']],
            ['name' => 'Cafetera Programable 12 Tazas', 'description' => 'Cafetera de goteo con temporizador programable.', 'price' => 45.50, 'stock' => 25, 'categories' => ['Electrodomésticos']],
            ['name' => 'Plancha a Vapor Cerámica', 'description' => 'Plancha con suela cerámica antiadherente y vapor vertical.', 'price' => 24.99, 'stock' => 60, 'categories' => ['Electrodomésticos']],
            ['name' => 'Batidora de Pie 5.5L', 'description' => 'Batidora de pie profesional con 6 velocidades.', 'price' => 129.00, 'stock' => 7, 'categories' => ['Electrodomésticos', 'Cocina']],

            // Muebles
            ['name' => 'Juego de Sala 3 Piezas', 'description' => 'Sofá, loveseat y sillón individual en tela antimanchas.', 'price' => 649.00, 'stock' => 6, 'categories' => ['Muebles']],
            ['name' => 'Comedor 6 Sillas Madera', 'description' => 'Mesa de comedor en madera maciza con 6 sillas tapizadas.', 'price' => 549.00, 'stock' => 4, 'categories' => ['Muebles']],
            ['name' => 'Cama Queen Base y Cabecera', 'description' => 'Cama tamaño queen con base tapizada y cabecera capitoné.', 'price' => 399.00, 'stock' => 10, 'categories' => ['Muebles']],
            ['name' => 'Closet 6 Puertas', 'description' => 'Closet modular de 6 puertas con espejo central.', 'price' => 329.00, 'stock' => 9, 'categories' => ['Muebles']],
            ['name' => 'Mesa de Centro Vidrio Templado', 'description' => 'Mesa de centro con base metálica y tope de vidrio templado.', 'price' => 89.90, 'stock' => 15, 'categories' => ['Muebles']],

            // Cocina
            ['name' => 'Juego de Ollas Antiadherentes 10 Piezas', 'description' => 'Set de ollas y sartenes con recubrimiento antiadherente.', 'price' => 79.99, 'stock' => 22, 'categories' => ['Cocina']],
            ['name' => 'Set de Cuchillos Profesionales', 'description' => 'Set de 6 cuchillos de acero inoxidable con soporte de madera.', 'price' => 34.50, 'stock' => 40, 'categories' => ['Cocina']],
            ['name' => 'Vajilla 20 Piezas Porcelana', 'description' => 'Vajilla completa de porcelana para 4 personas.', 'price' => 59.90, 'stock' => 17, 'categories' => ['Cocina']],
            ['name' => 'Sartén Wok Antiadherente', 'description' => 'Sartén wok de 32cm con mango ergonómico.', 'price' => 19.99, 'stock' => 50, 'categories' => ['Cocina']],

            // Juguetería
            ['name' => 'Muñeca Interactiva Bebé Llorón', 'description' => 'Muñeca interactiva que llora, ríe y balbucea.', 'price' => 24.99, 'stock' => 33, 'categories' => ['Juguetería']],
            ['name' => 'Pista de Carros Eléctrica', 'description' => 'Pista de carreras eléctrica con dos controles.', 'price' => 44.90, 'stock' => 14, 'categories' => ['Juguetería']],
            ['name' => 'Set de Bloques de Construcción 500pzs', 'description' => 'Bloques de construcción compatibles, set de 500 piezas.', 'price' => 29.99, 'stock' => 28, 'categories' => ['Juguetería']],
            ['name' => 'Bicicleta Infantil Rin 16', 'description' => 'Bicicleta infantil con rines auxiliares y casco incluido.', 'price' => 89.00, 'stock' => 6, 'categories' => ['Juguetería']],
            ['name' => 'Peluche Gigante Oso 1m', 'description' => 'Peluche de oso de 1 metro, ultra suave.', 'price' => 34.99, 'stock' => 2, 'categories' => ['Juguetería']],

            // Textiles y Blancos
            ['name' => 'Juego de Sábanas Queen 4 Piezas', 'description' => 'Sábanas de microfibra, tamaño queen, 4 piezas.', 'price' => 29.99, 'stock' => 55, 'categories' => ['Textiles y Blancos']],
            ['name' => 'Cobija Térmica Matrimonial', 'description' => 'Cobija térmica ultra suave tamaño matrimonial.', 'price' => 24.50, 'stock' => 38, 'categories' => ['Textiles y Blancos']],
            ['name' => 'Toallas de Baño Egipcias (Set 4)', 'description' => 'Set de 4 toallas de algodón egipcio de alta absorción.', 'price' => 22.99, 'stock' => 42, 'categories' => ['Textiles y Blancos']],
            ['name' => 'Cortinas Blackout 2 Paneles', 'description' => 'Cortinas blackout que bloquean el 100% de la luz.', 'price' => 34.90, 'stock' => 19, 'categories' => ['Textiles y Blancos']],

            // Electrónica
            ['name' => 'Televisor LED 50" 4K', 'description' => 'Smart TV LED 50 pulgadas resolución 4K UHD.', 'price' => 429.99, 'stock' => 11, 'categories' => ['Electrónica']],
            ['name' => 'Barra de Sonido Bluetooth', 'description' => 'Barra de sonido 2.1 con subwoofer inalámbrico.', 'price' => 89.90, 'stock' => 16, 'categories' => ['Electrónica']],
            ['name' => 'Audífonos Inalámbricos', 'description' => 'Audífonos Bluetooth con cancelación de ruido.', 'price' => 29.99, 'stock' => 70, 'categories' => ['Electrónica']],
            ['name' => 'Parlante Portátil Resistente al Agua', 'description' => 'Parlante Bluetooth portátil, resistente a salpicaduras.', 'price' => 39.50, 'stock' => 25, 'categories' => ['Electrónica']],

            // Descontinuados / inactivos
            ['name' => 'Ventilador de Pedestal 18"', 'description' => 'Ventilador de pedestal, 3 velocidades, modelo descontinuado.', 'price' => 29.99, 'stock' => 14, 'categories' => ['Descontinuados'], 'is_active' => false],
            ['name' => 'Radio Reloj Despertador AM/FM', 'description' => 'Radio reloj despertador con pantalla LED, modelo descontinuado.', 'price' => 12.99, 'stock' => 9, 'categories' => ['Descontinuados'], 'is_active' => false],
            ['name' => 'Plancha para Cabello Cerámica', 'description' => 'Plancha alisadora de cabello, línea descontinuada.', 'price' => 18.50, 'stock' => 5, 'categories' => ['Descontinuados'], 'is_active' => false],
        ];

        $categoriesByName = Category::query()->pluck('id', 'name');

        foreach ($products as $definition) {
            $categoryNames = $definition['categories'];
            unset($definition['categories']);

            $definition['is_active'] ??= true;

            $product = Product::factory()->create($definition);

            $categoryIds = collect($categoryNames)
                ->map(fn (string $name) => $categoriesByName[$name])
                ->all();

            $product->categories()->attach($categoryIds);
        }
    }
}
