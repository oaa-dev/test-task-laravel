<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;


class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $product_faker = Faker::create('en_US');

        // foreach (range(1, 10) as $index) {
        //     DB::table('products')->insert([
        //         'name' => $product_faker->word,
        //         'description' => $product_faker->sentence,
        //         'price' => $product_faker->randomFloat(2, 10, 100),
        //         'created_at' => now(),
        //     ]);
        // }

        \App\Models\Product::factory(10)->create();
    }
}
