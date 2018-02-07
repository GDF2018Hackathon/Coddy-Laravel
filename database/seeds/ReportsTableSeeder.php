<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class ReportsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $generated = serialize(json_decode(file_get_contents(storage_path('reports/generated.json'))));

        DB::table('reports')->insert([
            'code' => 'X12652QSD',
            'repo_id' => 79894609,
            'email' => 'crea2luxe@gmail.com',
            'user_id' => 1,
            'project_name' => "my-projects-framework",
            'content' => $generated,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s')
        ]);
    }
}
