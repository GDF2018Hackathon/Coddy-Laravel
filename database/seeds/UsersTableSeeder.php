<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      DB::table('users')->insert([
        'name' => 'Dinho Ryoh',
        'nickname' => 'DinhoRyoh',
        'github_id' => 18353235,
        'email' => 'dyner@hotmail.fr',
        'newsletter' => false
      ]);
    }
}
