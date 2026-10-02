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
        foreach ([
            ['name' => 'Kuliner', 'slug' => 'kuliner'],
            ['name' => 'Jasa', 'slug' => 'jasa'],
            ['name' => 'Fashion', 'slug' => 'fashion'],
            ['name' => 'Teknologi', 'slug' => 'teknologi'],
        ] as $category) {
            Category::firstOrCreate(['slug' => $category['slug']], $category);
        }
    }
}
