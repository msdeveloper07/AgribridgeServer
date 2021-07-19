<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Applications extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('applications', function (Blueprint $table) {
            $table->id('app_id');
            $table->string('app_name', 45)->nullable();
            $table->string('app_description', 150)->nullable();
            $table->timestamp('create_time')->useCurrent();
            $table->timestamp('update_time')->useCurrent();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /*
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('applications');
    }
}
