<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UserRoles extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_roles', function (Blueprint $table) {
            $table->id('urs_id');

            $table->unsignedBigInteger('usr_id');
            $table->foreign('usr_id')->references('usr_id')->on('users')->index('urs_usr_fk');

            $table->unsignedBigInteger('rol_id');
            $table->foreign('rol_id')->references('rol_id')->on('roles')->index('urs_rol_fk');
            $table->integer('create_userid')->nullable();
            $table->integer('update_userid')->nullable();
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
        Schema::dropIfExists('user_roles');
    }
}
