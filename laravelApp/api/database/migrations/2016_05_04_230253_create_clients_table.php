<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->string('lastname', 30);
            $table->string('name', 30);
            $table->string('address', 30)->nullable();
            $table->string('telephone1', 15)->nullable();
            $table->string('telephone2', 15)->nullable();
            $table->string('email', 30)->nullable();
            $table->string('cuit',12)->nullable();
            $table->string('comments')->nullable();
            $table->timestamps();

            // Foreign Key      clients.user_id -> users.id
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
        Schema::drop('clients');
    }
}