<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class CategoryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      DB::table('categories')->insert([
          'name' => "presentation",
          'slug' => 'presentation',
          'status' => true,
          'created_at' => Carbon::now()->format('Y-m-d H:i:s')
      ]);
      DB::table('categories')->insert([
          'name' => "request",
          'slug' => 'request',
          'status' => true,
          'created_at' => Carbon::now()->format('Y-m-d H:i:s')
      ]);
    }
}
