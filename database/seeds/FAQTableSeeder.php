<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class FAQTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      DB::table('faqs')->insert([
          'question' => "Who is Coddy ?",
          'answer' => 'he is a bug-eater and he is very hungry.',
          'category_id' => 1,
          'status' => true,
          'created_at' => Carbon::now()->format('Y-m-d H:i:s')
      ]);
      DB::table('faqs')->insert([
          'question' => "What Coddy can do ?",
          'answer' => 'He can scan your PHP projects from Github and Bitbucket.',
          'category_id' => 1,
          'status' => true,
          'created_at' => Carbon::now()->format('Y-m-d H:i:s')
      ]);
      DB::table('faqs')->insert([
          'question' => "Is it free ?",
          'answer' => 'Coddy does it for free !',
          'category_id' => 1,
          'status' => true,
          'created_at' => Carbon::now()->format('Y-m-d H:i:s')
      ]);
    }
}
