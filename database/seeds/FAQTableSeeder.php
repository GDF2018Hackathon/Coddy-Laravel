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
          'answer' => 'Coddy is a spider ! A freaking hungry bug-eater but he is here to help you.',
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
      DB::table('faqs')->insert([
          'question' => "How can I support Coddy ?",
          'answer' => 'You can donate on the website by PayPal or CryptoCurrency.',
          'category_id' => 1,
          'status' => true,
          'created_at' => Carbon::now()->format('Y-m-d H:i:s')
      ]);
      DB::table('faqs')->insert([
          'question' => "Do I need a Github/bitbucket account to use Coddy and why ?",
          'answer' => 'You need an account, Coddy is working around Github/Bitbucket API where datas are retrieved, and obviously to get your repositories in the list. Why ? We know by this fact, you can only scan your project, and that is what we exactly want.',
          'category_id' => 1,
          'status' => true,
          'created_at' => Carbon::now()->format('Y-m-d H:i:s')
      ]);
      DB::table('faqs')->insert([
          'question' => "What is the newsletter about ?",
          'answer' => "It permit us to introduce new Coddy's functions, our latest objectives and advices.",
          'category_id' => 1,
          'status' => true,
          'created_at' => Carbon::now()->format('Y-m-d H:i:s')
      ]);
      DB::table('faqs')->insert([
          'question' => "Hello, I launched the scan but I got an error. What should I do ?",
          'answer' => "Our staff is working on it, you'll recieve a mail as soon as possible.",
          'category_id' => 2,
          'status' => true,
          'created_at' => Carbon::now()->format('Y-m-d H:i:s')
      ]);
      DB::table('faqs')->insert([
          'question' => "Hello, how do I get a project from an organisation ? I am part of the organisation.",
          'answer' => "We are working on it, at the moment you can only scan your repositories, sorry for the convenience.",
          'category_id' => 2,
          'status' => true,
          'created_at' => Carbon::now()->format('Y-m-d H:i:s')
      ]);
      DB::table('faqs')->insert([
          'question' => "Hi ! I just wanted you to know that Coddy is amazing !",
          'answer' => "Thank you customer !",
          'category_id' => 2,
          'status' => true,
          'created_at' => Carbon::now()->format('Y-m-d H:i:s')
      ]);
    }
}
