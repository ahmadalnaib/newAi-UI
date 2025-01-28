<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class PlansTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
         DB::table('plans')->insert([
            'name' => 'Monthly',
            'price' => '1,00',
            'price_id' => 'price_1QldQ3EJu0Wa6WmyZXhCb3Vk',
        ]);
    }
}
