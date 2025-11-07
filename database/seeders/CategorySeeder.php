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
        $menu = [
            [
                'name' => 'Web Development',
                'description' => 'All kinds of web development services.',
                'in_menu' => true,
                'menu_order' => 1,
            ],
            [
                'name' => 'Graphic Design',
                'description' => 'Creative graphic design solutions.',
                'in_menu' => true,
                'menu_order' => 2,
            ],
            [
                'name' => 'Digital Marketing',
                'description' => 'Promote your business online.',
                'in_menu' => true,
                'menu_order' => 3,
            ],
            [
                'name' => 'Writing & Translation',
                'description' => 'Professional writing and translation services.',
                'in_menu' => true,
                'menu_order' => 4,
            ],
            [
                'name' => 'Video & Animation',
                'description' => 'Engaging video and animation content.',
                'in_menu' => true,
                'menu_order' => 5,
            ],
        ];

        foreach ($menu as $cat) {
            Category::create($cat);
        }
    }
}
