<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateActionLogTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('action_logs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('uuid')->unique();
            $table->string('action_name');
            $table->text('action_class');
            $table->boolean('is_reversed')->default(false);
            $table->integer('user_id')->unsigned()->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('action_logs');
    }
}
