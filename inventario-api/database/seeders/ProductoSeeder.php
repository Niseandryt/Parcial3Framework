<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Producto;

class ProductoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $productos = [
            'Mouse', 
            'Teclado',
            'Monitor',
            'Impresora',
        ];
        foreach ($productos as $nombre) {
            Producto::create([
                'nombre' => $nombre,
                'descripcion' => 'Descripción de ' . $nombre,
                'precio' => rand(25, 500),
                'stock' => rand(1, 50),
            ]);
        }
        Producto::create([
            'nombre' => 'Auriculares',
            'descripcion' => 'Descripción de Auriculares',
            'precio' => rand(25, 500),
            'stock' => 0,
        ]);
    }
}
