<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('logs', function (Blueprint $table) {
          // $table->engine = 'InnoDB';
          //  $table->bigIncrements('id');
          //  $table->string('channel', 50)->index();
          //  $table->text('message');
          //  $table->string('level', 50)->index();
          //  $table->string('level_name', 100);
          //  $table->text('context');
          //  $table->text('formatted');
          //  $table->dateTime('created_at');
          $table->engine = 'InnoDB';
              $table->bigIncrements('id');
              $table->string('instance')->index();
              $table->string('channel')->index();
              $table->string('level')->index();
              $table->string('level_name');
              $table->text('message');
              $table->text('context');
              $table->integer('remote_addr')->nullable()->unsigned();
              $table->string('user_agent')->nullable();
              $table->integer('created_by')->nullable()->index();
              $table->dateTime('created_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('logs');
    }
}
