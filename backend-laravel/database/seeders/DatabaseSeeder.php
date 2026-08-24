<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $category = Category::firstOrCreate(
            ['name' => 'Fast Food'],
            ['parent_id' => 0, 'description' => 'Makanan cepat saji']
        );

        $products = [
            [
                'name' => 'Nasi Goreng Spesial',
                'primary_image' => 'nasi-goreng.jpg',
                'description' => 'Nasi goreng dengan telur dan ayam suwir',
                'price' => 20000,
            ],
            [
                'name' => 'Burger Spesial',
                'primary_image' => 'burger.jpg',
                'description' => 'Burger daging sapi premium dengan keju leleh',
                'price' => 25000,
            ],
            [
                'name' => 'Kentang Goreng',
                'primary_image' => 'fries.jpg',
                'description' => 'Kentang goreng renyah dengan saus spesial',
                'price' => 15000,
            ],
            [
                'name' => 'Es Teh Manis',
                'primary_image' => 'drink.jpg',
                'description' => 'Es teh manis segar',
                'price' => 8000,
            ],
            [
                'name' => 'Soto Betawi',
                'primary_image' => 'soto-betawi.jpg',
                'description' => 'Soto betawi dengan kuah santan gurih dan daging sapi empuk',
                'price' => 28000,
            ],
            [
                'name' => 'Sate Kambing',
                'primary_image' => 'sate-kambing.jpg',
                'description' => 'Sate kambing bakar dengan bumbu kecap khas',
                'price' => 35000,
            ],
        ];

        foreach ($products as $item) {
            $slug = \Illuminate\Support\Str::slug($item['name']);

            Product::firstOrCreate(
                ['slug' => $slug],
                [
                    'name' => $item['name'],
                    'category_id' => $category->id,
                    // Seed images live in public/images/seed, served directly
                    // by Nginx — not the storage/ disk, so they survive Pod
                    // restarts since they are baked into the Docker image.
                    'primary_image' => 'seed/' . $item['primary_image'],
                    'description' => $item['description'],
                    'price' => $item['price'],
                    'quantity' => 100,
                    'status' => 1,
                    'sale_price' => 0,
                ]
            );
        }
    }
}
