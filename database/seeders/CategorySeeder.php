<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            'ALCOHOL',
            'BABY FOODS',
            'BAKING INGREDIENTS',
            'BEVERAGES',
            'BODY CARE',
            'CANNED GOODS',
            'CANDIES & CHOCOLATES',
            'CIGARETTES',
            'COFFEE MIXES',
            'CONDIMENTS & SAUCES',
            'CONFECTIONERIES',
            'DIAPERS',
            'DRY GOODS',
            'FACIAL CARE',
            'FEMININE CARE',
            'FROZEN GOODS',
            'FRUITS',
            'HAIR CARE',
            'HOUSEHOLD SUPPLIES',
            'ICE CREAM',
            'JUICES',
            'LAUNDRY PRODUCTS',
            'LIQUOR & WINES',
            'MEAT PRODUCTS',
            'MEDICARE & MEDICINE',
            'MILK PRODUCTS',
            'NAIL CARE',
            'ORAL CARE',
            'PASTA',
            'PERFUMES & FRAGRANCES',
            'PLASTIC WARE & MERCHANDISE',
            'SANITARY',
            'SCHOOL & OFFICE SUPPLIES',
            'SKIN CARE',
            'SNACKS & BISCUITS',
            'SOAP',
            'SPREAD & CHEESE',
            'TOYS',
            'VEGETABLES & SPICES',
            'OTHERS',
        ];

        $data = array_map(fn($name) => [
            'category_name' => $name,
            'created_at'    => now(),
            'updated_at'    => now(),
        ], $categories);

        DB::table('category')->insert($data);
    }
}