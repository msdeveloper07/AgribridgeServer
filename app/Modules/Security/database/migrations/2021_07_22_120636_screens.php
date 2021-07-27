<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Screens extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('screens', function (Blueprint $table) {
            $table->id('scr_id');

            $table->unsignedBigInteger('mod_id');
            $table->foreign('mod_id')->references('mod_id')->on('modules')->index('scr_mod_fk');

            $table->string('scr_name', 45)->nullable();
            $table->string('scr_description', 45)->nullable();
            $table->integer('scr_order')->nullable();
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
        Schema::dropIfExists('screens');
    }
}
