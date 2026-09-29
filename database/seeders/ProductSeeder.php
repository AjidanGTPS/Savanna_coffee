<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductOption;
use App\Models\ProductOptionGroup;
use App\Models\ProductSizePrice;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $coffee = Category::where('slug', 'coffee')->first();
        $makanan = Category::where('slug', 'makanan')->first();
        $dessert = Category::where('slug', 'dessert-minuman')->first();

        // ====== COFFEE ======
        $coffeeProducts = [
            // Espresso Based - no size
            ['name' => 'Espresso Single', 'base_price' => 18000, 'has_size' => false],
            ['name' => 'Espresso Double', 'base_price' => 22000, 'has_size' => false],
            // with size
            ['name' => 'Americano', 'base_price' => 0, 'has_size' => true, 'sizes' => ['S' => 20000, 'M' => 25000, 'L' => 30000]],
            ['name' => 'Cappuccino', 'base_price' => 0, 'has_size' => true, 'sizes' => ['S' => 25000, 'M' => 30000, 'L' => 35000]],
            ['name' => 'Latte', 'base_price' => 0, 'has_size' => true, 'sizes' => ['S' => 28000, 'M' => 33000, 'L' => 38000]],
            ['name' => 'Macchiato', 'base_price' => 0, 'has_size' => true, 'sizes' => ['S' => 26000, 'M' => 31000, 'L' => 36000]],
            // Flavored Coffee
            ['name' => 'Caramel Macchiato', 'base_price' => 0, 'has_size' => true, 'sizes' => ['S' => 30000, 'M' => 35000, 'L' => 40000]],
            ['name' => 'Vanilla Latte', 'base_price' => 0, 'has_size' => true, 'sizes' => ['S' => 30000, 'M' => 35000, 'L' => 40000]],
            ['name' => 'Hazelnut Cappuccino', 'base_price' => 0, 'has_size' => true, 'sizes' => ['S' => 30000, 'M' => 35000, 'L' => 40000]],
            // Cold Coffee
            ['name' => 'Iced Americano', 'base_price' => 0, 'has_size' => true, 'sizes' => ['S' => 22000, 'M' => 27000, 'L' => 32000]],
            ['name' => 'Iced Latte', 'base_price' => 0, 'has_size' => true, 'sizes' => ['S' => 30000, 'M' => 35000, 'L' => 40000]],
            ['name' => 'Cold Brew', 'base_price' => 0, 'has_size' => true, 'sizes' => ['S' => 25000, 'M' => 30000, 'L' => 35000]],
        ];

        foreach ($coffeeProducts as $index => $data) {
            $product = Product::firstOrCreate(
                ['name' => $data['name'], 'category_id' => $coffee->id],
                [
                    'category_id' => $coffee->id,
                    'base_price' => $data['base_price'],
                    'has_size' => $data['has_size'],
                    'is_available' => true,
                    'sort_order' => $index + 1,
                ]
            );

            if ($data['has_size'] && isset($data['sizes'])) {
                foreach ($data['sizes'] as $size => $price) {
                    ProductSizePrice::firstOrCreate(
                        ['product_id' => $product->id, 'size' => $size],
                        ['price' => $price]
                    );
                }
            }

            // Coffee customization options
            $this->createCoffeeOptions($product);
        }

        // ====== MAKANAN ======
        $makananProducts = [
            // Pastry & Bakery
            ['name' => 'Croissant', 'base_price' => 25000],
            ['name' => 'Pain au Chocolat', 'base_price' => 28000],
            ['name' => 'Donut (2pcs)', 'base_price' => 30000],
            ['name' => 'Muffin', 'base_price' => 20000],
            // Sandwich & Wrap
            ['name' => 'Smoked Chicken Sandwich', 'base_price' => 45000],
            ['name' => 'Tuna Salad Wrap', 'base_price' => 42000],
            ['name' => 'Veggie Sandwich', 'base_price' => 38000],
            ['name' => 'Turkey Bacon Club', 'base_price' => 52000],
            // Salad
            ['name' => 'Caesar Salad', 'base_price' => 48000],
            ['name' => 'Garden Fresh Salad', 'base_price' => 40000],
            ['name' => 'Quinoa Power Salad', 'base_price' => 55000],
        ];

        foreach ($makananProducts as $index => $data) {
            $product = Product::firstOrCreate(
                ['name' => $data['name'], 'category_id' => $makanan->id],
                [
                    'category_id' => $makanan->id,
                    'base_price' => $data['base_price'],
                    'has_size' => false,
                    'is_available' => true,
                    'sort_order' => $index + 1,
                ]
            );

            // Food options for sandwiches/salads
            if (in_array($data['name'], ['Smoked Chicken Sandwich', 'Tuna Salad Wrap', 'Veggie Sandwich', 'Turkey Bacon Club', 'Caesar Salad', 'Garden Fresh Salad', 'Quinoa Power Salad'])) {
                $this->createFoodOptions($product);
            }

            if ($data['name'] === 'Muffin') {
                $group = ProductOptionGroup::firstOrCreate(
                    ['product_id' => $product->id, 'name' => 'Pilihan Rasa'],
                    ['type' => 'single', 'is_required' => true, 'sort_order' => 1]
                );
                foreach (['Choco', 'Vanilla', 'Blueberry'] as $i => $rasa) {
                    ProductOption::firstOrCreate(
                        ['product_option_group_id' => $group->id, 'name' => $rasa],
                        ['additional_price' => 0, 'is_default' => $i === 0, 'sort_order' => $i + 1]
                    );
                }
            }
        }

        // ====== DESSERT & MINUMAN ======
        $dessertProducts = [
            // Dessert
            ['name' => 'Choco Cake Slice', 'base_price' => 35000],
            ['name' => 'Cheesecake', 'base_price' => 38000],
            ['name' => 'Tiramisu', 'base_price' => 40000],
            ['name' => 'Brownies', 'base_price' => 28000],
            ['name' => 'Macarons (3pcs)', 'base_price' => 32000],
            // Tea & Hot Drinks
            ['name' => 'English Breakfast Tea', 'base_price' => 18000],
            ['name' => 'Green Tea', 'base_price' => 0, 'has_size' => true, 'sizes' => ['S' => 15000, 'M' => 18000, 'L' => 22000]],
            ['name' => 'Chamomile Lavender Tea', 'base_price' => 20000],
            ['name' => 'Hot Chocolate', 'base_price' => 0, 'has_size' => true, 'sizes' => ['S' => 22000, 'M' => 27000, 'L' => 32000]],
            ['name' => 'Chai Latte', 'base_price' => 0, 'has_size' => true, 'sizes' => ['S' => 25000, 'M' => 30000, 'L' => 35000]],
            // Fresh Juice & Smoothie
            ['name' => 'Fresh Orange Juice', 'base_price' => 28000],
            ['name' => 'Mango Smoothie', 'base_price' => 32000],
            ['name' => 'Strawberry Banana Smoothie', 'base_price' => 35000],
            ['name' => 'Green Detox Juice', 'base_price' => 38000],
        ];

        foreach ($dessertProducts as $index => $data) {
            $hasSize = $data['has_size'] ?? false;
            $product = Product::firstOrCreate(
                ['name' => $data['name'], 'category_id' => $dessert->id],
                [
                    'category_id' => $dessert->id,
                    'base_price' => $data['base_price'],
                    'has_size' => $hasSize,
                    'is_available' => true,
                    'sort_order' => $index + 1,
                ]
            );

            if ($hasSize && isset($data['sizes'])) {
                foreach ($data['sizes'] as $size => $price) {
                    ProductSizePrice::firstOrCreate(
                        ['product_id' => $product->id, 'size' => $size],
                        ['price' => $price]
                    );
                }
            }

            // Dessert topping option
            $dessertNames = ['Choco Cake Slice', 'Cheesecake', 'Tiramisu', 'Brownies', 'Macarons (3pcs)'];
            if (in_array($data['name'], $dessertNames)) {
                $group = ProductOptionGroup::firstOrCreate(
                    ['product_id' => $product->id, 'name' => 'Tambahan Topping'],
                    ['type' => 'multiple', 'is_required' => false, 'sort_order' => 1]
                );
                ProductOption::firstOrCreate(
                    ['product_option_group_id' => $group->id, 'name' => 'Whipped Cream'],
                    ['additional_price' => 5000, 'is_default' => false, 'sort_order' => 1]
                );
            }
        }
    }

    private function createCoffeeOptions(Product $product): void
    {
        $groups = [
            [
                'name' => 'Pilihan Susu',
                'type' => 'single',
                'is_required' => false,
                'sort_order' => 1,
                'options' => [
                    ['name' => 'Regular', 'additional_price' => 0, 'is_default' => true],
                    ['name' => 'Oat Milk', 'additional_price' => 5000, 'is_default' => false],
                    ['name' => 'Almond Milk', 'additional_price' => 5000, 'is_default' => false],
                ],
            ],
            [
                'name' => 'Level Manis',
                'type' => 'single',
                'is_required' => false,
                'sort_order' => 2,
                'options' => [
                    ['name' => 'Normal', 'additional_price' => 0, 'is_default' => true],
                    ['name' => 'Less Sugar', 'additional_price' => 0, 'is_default' => false],
                    ['name' => 'Tanpa Gula', 'additional_price' => 0, 'is_default' => false],
                ],
            ],
            [
                'name' => 'Level Es',
                'type' => 'single',
                'is_required' => false,
                'sort_order' => 3,
                'options' => [
                    ['name' => 'Reguler Es', 'additional_price' => 0, 'is_default' => true],
                    ['name' => 'Banyak Es', 'additional_price' => 0, 'is_default' => false],
                    ['name' => 'Tanpa Es', 'additional_price' => 0, 'is_default' => false],
                ],
            ],
            [
                'name' => 'Add-on',
                'type' => 'multiple',
                'is_required' => false,
                'sort_order' => 4,
                'options' => [
                    ['name' => 'Extra Shot Espresso', 'additional_price' => 5000, 'is_default' => false],
                    ['name' => 'Syrup Tambahan', 'additional_price' => 3000, 'is_default' => false],
                ],
            ],
        ];

        foreach ($groups as $groupData) {
            $group = ProductOptionGroup::firstOrCreate(
                ['product_id' => $product->id, 'name' => $groupData['name']],
                [
                    'type' => $groupData['type'],
                    'is_required' => $groupData['is_required'],
                    'sort_order' => $groupData['sort_order'],
                ]
            );

            foreach ($groupData['options'] as $i => $optionData) {
                ProductOption::firstOrCreate(
                    ['product_option_group_id' => $group->id, 'name' => $optionData['name']],
                    [
                        'additional_price' => $optionData['additional_price'],
                        'is_default' => $optionData['is_default'],
                        'sort_order' => $i + 1,
                    ]
                );
            }
        }
    }

    private function createFoodOptions(Product $product): void
    {
        $group = ProductOptionGroup::firstOrCreate(
            ['product_id' => $product->id, 'name' => 'Preferensi'],
            ['type' => 'multiple', 'is_required' => false, 'sort_order' => 1]
        );

        $preferences = ['No Onion', 'No Mayo', 'Extra Sauce', 'Dressing Terpisah'];
        foreach ($preferences as $i => $pref) {
            ProductOption::firstOrCreate(
                ['product_option_group_id' => $group->id, 'name' => $pref],
                ['additional_price' => 0, 'is_default' => false, 'sort_order' => $i + 1]
            );
        }
    }
}
