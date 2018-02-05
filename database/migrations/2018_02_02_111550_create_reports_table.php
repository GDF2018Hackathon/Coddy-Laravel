<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reports', function (Blueprint $table) {
          $table->increments('id');
          $table->string('code',25)->unique();
          $table->string('repo_id');
          $table->string('project_name');
          $table->integer('user_id')->unsigned();
          $table->string('email');
          $table->boolean('public')->default(true);
          $table->longText('content');
          $table->dateTimeTz('created_at');
          $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('reports', function (Blueprint $table) {
          Schema::dropIfExists('reports');

        });
    }
}
