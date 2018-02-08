<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

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
        'name' => 'Klenzo',
        'nickname' => 'klenzo',
        'social_id' => 10270234,
        'email' => 'crea2luxe@hotmail.fr',
        'newsletter' => false
      ]);
    }
}
