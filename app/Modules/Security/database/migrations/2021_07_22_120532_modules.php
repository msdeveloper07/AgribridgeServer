<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Modules extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('modules', function (Blueprint $table) {
            $table->id('mod_id');

            $table->unsignedBigInteger('app_id');
            $table->foreign('app_id')->references('app_id')->on('applications')->index('mod_app_fk');
            $table->integer('parent_mod_id')->nullable();
            $table->string('mod_code', 25)->nullable();
            $table->string('mod_description', 75)->nullable();
            $table->integer('order_id')->nullable();
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
        Schema::dropIfExists('modules');
    }
}
