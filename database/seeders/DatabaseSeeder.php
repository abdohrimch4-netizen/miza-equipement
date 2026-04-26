<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // كنعيطو لـ Faker بالفرنسية باش يعطينا داطا مقادة
        $faker = Faker::create('fr_FR');

        // غانديرو حلقة (Loop) باش نكرييو 50 منتج
        for ($i = 0; $i < 50; $i++) {
            DB::table('products')->insert([
                // كنعزلو سميات ديال السلعة اللي كتتباع فالمحل
                'name' => $faker->randomElement(['Réfrigérateur', 'Machine à laver', 'Smart TV', 'Four Encastrable', 'Mixeur', 'Aspirateur', 'Climatiseur']) . ' ' . $faker->word(),
                'reference' => 'REF-' . $faker->unique()->numberBetween(1000, 9999),
                'description' => $faker->realText(200), // وصف عشوائي
                'price' => $faker->randomFloat(2, 300, 15000), // ثمن بين 300 و 15000 درهم
                'stock' => $faker->numberBetween(0, 30), // ستوك بين 0 و 30 (باش يبان لينا شكون اللي Out of stock)
                'image' => null, // غانخليو التصويرة خاوية باش تبان غير التصويرة الاحتياطية
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}