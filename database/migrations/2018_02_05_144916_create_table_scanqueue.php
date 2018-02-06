<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableScanqueue extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('scanqueue', function (Blueprint $table) {
            $table->increments('id');
            $table->string('user_id');
            $table->string('tmp_id');
            $table->string('github_fullname');
            $table->string('path')->default('/');
            $table->string('branch')->default('master');
            $table->boolean('statut')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('scanqueue');
    }
}