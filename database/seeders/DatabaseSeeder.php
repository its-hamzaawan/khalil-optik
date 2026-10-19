<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Admin account — CHANGE THE PASSWORD after your first login (see README).
        User::updateOrCreate(
            ['email' => env('ADMIN_EMAIL', 'admin@khaliloptik.pk')],
            ['name' => 'Khalil Optik Admin', 'password' => Hash::make(env('ADMIN_PASSWORD', 'ChangeMe@1960'))],
        );

        // Demo catalogue so the site isn't empty. Replace with the shop's real stock from /admin.
        $demo = [
            ['Classic Rectangle Frame', 'spectacles', 'House Collection', 'men', 'Rectangle', 4500, null, 8, true],
            ['Round Metal Frame', 'spectacles', 'House Collection', 'unisex', 'Round', 5200, 4600, 5, true],
            ['Cat-Eye Acetate Frame', 'spectacles', 'House Collection', 'women', 'Cat-eye', 6000, null, 4, false],
            ['Kids Flexible Frame', 'spectacles', 'House Collection', 'kids', 'Oval', 3200, null, 10, false],
            ['Polarized Aviator', 'sunglasses', 'House Collection', 'unisex', 'Aviator', 3800, 3300, 6, true],
            ['Square UV400 Sunglasses', 'sunglasses', 'House Collection', 'men', 'Square', 2900, null, 9, false],
            ['Oversized Sunglasses', 'sunglasses', 'House Collection', 'women', 'Oversized', 3400, null, 3, true],
            ['Monthly Clear Contact Lenses (pair)', 'contact-lenses', null, 'unisex', null, 2500, null, 20, true],
            ['Daily Disposable Lenses (30 pack)', 'contact-lenses', null, 'unisex', null, 4200, null, 12, false],
            ['Lens Cleaning Kit', 'accessories', null, 'unisex', null, 450, null, 30, false],
            ['Hard Spectacle Case', 'accessories', null, 'unisex', null, 600, null, 25, false],
        ];

        foreach ($demo as [$name, $category, $brand, $gender, $shape, $price, $sale, $stock, $featured]) {
            Product::updateOrCreate(['name' => $name], [
                'category' => $category,
                'brand' => $brand,
                'gender' => $gender,
                'frame_shape' => $shape,
                'description' => 'Sample product for the demo catalogue. Edit this description from the admin panel.',
                'price' => $price,
                'sale_price' => $sale,
                'stock' => $stock,
                'is_featured' => $featured,
                'is_active' => true,
            ]);
        }
    }
}
