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
            $table->integer('active');
            $table->integer('id_user');
            $table->string('lastname', 30);
            $table->string('name', 30);
            $table->string('address', 30);
            $table->string('telephone1', 15);
            $table->string('telephone2', 15);
            $table->string('email', 30);
            $table->string('cuit',12);
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
        Schema::drop('clients');
    }
}