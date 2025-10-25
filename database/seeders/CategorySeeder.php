<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $mainCategories = Category::factory()->count(10)->create();

       
        Category::factory()->count(10)->create([
            'parent_id' => function () use ($mainCategories) {
                return $mainCategories->random()->id;
            },
        ]);
    }
}
