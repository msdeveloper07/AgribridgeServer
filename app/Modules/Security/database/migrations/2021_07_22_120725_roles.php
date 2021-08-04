<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Roles extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->id('rol_id');

            $table->unsignedBigInteger('org_id');
            $table->foreign('org_id')->references('org_id')->on('organizations')->index('rol_org_fk');

            $table->string('role_name', 45)->nullable();
            $table->integer('created_userid')->nullable();
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
        Schema::dropIfExists('roles');
    }
}
