<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Anjing',  'slug' => 'dog',    'icon' => '🐶'],
            ['name' => 'Kucing',  'slug' => 'cat',    'icon' => '🐱'],
            ['name' => 'Burung',  'slug' => 'bird',   'icon' => '🐦'],
            ['name' => 'Ikan',    'slug' => 'fish',   'icon' => '🐟'],
            ['name' => 'Kelinci', 'slug' => 'rabbit', 'icon' => '🐰'],
        ];

        foreach ($categories as $cat) {
            Category::firstOrCreate(['slug' => $cat['slug']], $cat);
        }
    }
}
