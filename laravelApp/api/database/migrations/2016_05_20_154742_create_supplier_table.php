<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSupplierTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('suppliers', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('active')->default(1);
            $table->string('description', 50)->nullable();
            $table->string('tel', 15)->nullable();
            $table->string('adress', 30)->unique();
            $table->string('email', 30)->nullable();
            $table->string('responsible', 30)->nullable();
            $table->rememberToken();
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
         Schema::drop('suppliers');
    }
}
