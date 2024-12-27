<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class ReviewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $review_faker = Faker::create('en_US');

        $products = DB::table('products')->pluck('id');

        for ($index=0; $index < 20; $index++) { 
            DB::table('reviews')->insert([
                'user_name' => $review_faker->userName,
                'product_id' => $review_faker->randomElement($products),
                'rating' => $review_faker->numberBetween(1, 5),
                'comment' => $review_faker->sentence,
                'created_at' => now(),
            ]);
        }
    }
}
