<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RolePrivileges extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('role_privileges', function (Blueprint $table) {
            $table->id('rpv_id');

            $table->unsignedBigInteger('rol_id');
            $table->foreign('rol_id')->references('rol_id')->on('roles')->index('rpv_rol_fk');

            $table->unsignedBigInteger('scr_id');
            $table->foreign('scr_id')->references('scr_id')->on('screens')->index('rpv_scr_fk');

            $table->tinyInteger('view_access')->nullable();
            $table->tinyInteger('edit_access')->nullable();
            $table->integer('create_userid')->nullable();
            $table->integer('update_userid')->nullable();
            $table->timestamp('create_time')->useCurrent();
            $table->timestamp('update_time')->useCurrent();
            $table->softDeletes();
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
        Schema::dropIfExists('role_privileges');
    }
}
