<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'title' => 'Salário',
                'icon' => 'banknotes',
            ],
            [
                'title' => 'Extras',
                'icon' => 'briefcase',
            ],
            [
                'title' => 'Investimentos',
                'icon' => 'chart-bar',
            ],
            [
                'title' => 'Moradia',
                'icon' => 'home',
            ],
            [
                'title' => 'Alimentação',
                'icon' => 'shopping-cart',
            ],
            [
                'title' => 'Transporte',
                'icon' => 'car',
            ],
            [
                'title' => 'Lazer',
                'icon' => 'face-smile',
            ],
            [
                'title' => 'Saúde',
                'icon' => 'heart',
            ],
            [
                'title' => 'Educação',
                'icon' => 'book-open',
            ],
        ];

        DB::table('categories')->upsert(
            $categories,
            ['title'],
            ['icon']
        );
    }
}
