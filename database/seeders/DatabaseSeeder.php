<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use App\Models\Hall;
use App\Models\Table;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Create admin user
        User::create([
            'name' => 'Admin',
            'phone' => '1234567890',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        // Create sample categories
        $categories = [
            ['name' => 'Pizza', 'status' => true],
            ['name' => 'Burgers', 'status' => true],
            ['name' => 'Pasta', 'status' => true],
            ['name' => 'Salads', 'status' => true],
            ['name' => 'Drinks', 'status' => true],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }

        // Create sample products
        $products = [
            ['name' => 'Margherita Pizza', 'code' => 'PZ001', 'price' => 12.99, 'stock' => 50, 'category_id' => 1],
            ['name' => 'Pepperoni Pizza', 'code' => 'PZ002', 'price' => 14.99, 'stock' => 50, 'category_id' => 1],
            ['name' => 'Cheeseburger', 'code' => 'BG001', 'price' => 8.99, 'stock' => 50, 'category_id' => 2],
            ['name' => 'Chicken Burger', 'code' => 'BG002', 'price' => 9.99, 'stock' => 50, 'category_id' => 2],
            ['name' => 'Spaghetti Carbonara', 'code' => 'PA001', 'price' => 11.99, 'stock' => 50, 'category_id' => 3],
            ['name' => 'Caesar Salad', 'code' => 'SL001', 'price' => 7.99, 'stock' => 50, 'category_id' => 4],
            ['name' => 'Coca-Cola', 'code' => 'DR001', 'price' => 2.50, 'stock' => 100, 'category_id' => 5],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }

        // Create sample halls and tables
        $hall1 = Hall::create(['name' => 'Main Hall', 'status' => true]);
        $hall2 = Hall::create(['name' => 'VIP Hall', 'status' => true]);

        for ($i = 1; $i <= 10; $i++) {
            Table::create([
                'name' => 'Table ' . $i,
                'hall_id' => $i <= 7 ? $hall1->id : $hall2->id,
                'capacity' => $i <= 7 ? 4 : 6,
                'status' => true,
            ]);
        }
    }
}