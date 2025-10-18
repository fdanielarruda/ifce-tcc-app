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
                'color' => 'text-green-600 bg-green-100',
            ],
            [
                'title' => 'Extras',
                'icon' => 'briefcase',
                'color' => 'text-emerald-600 bg-emerald-100',
            ],
            [
                'title' => 'Investimentos',
                'icon' => 'chart-bar',
                'color' => 'text-blue-600 bg-blue-100',
            ],
            [
                'title' => 'Moradia',
                'icon' => 'home',
                'color' => 'text-red-600 bg-red-100',
            ],
            [
                'title' => 'Alimentação',
                'icon' => 'shopping-cart',
                'color' => 'text-orange-600 bg-orange-100',
            ],
            [
                'title' => 'Transporte',
                'icon' => 'car',
                'color' => 'text-indigo-600 bg-indigo-100',
            ],
            [
                'title' => 'Lazer',
                'icon' => 'face-smile',
                'color' => 'text-pink-600 bg-pink-100',
            ],
            [
                'title' => 'Saúde',
                'icon' => 'heart',
                'color' => 'text-teal-600 bg-teal-100',
            ],
            [
                'title' => 'Educação',
                'icon' => 'book-open',
                'color' => 'text-purple-600 bg-purple-100',
            ],
        ];

        DB::table('categories')->upsert(
            $categories,
            ['title'],
            ['icon', 'color']
        );
    }
}
