<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Locations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('locations', function (Blueprint $table) {
            $table->id('lcn_id');
            $table->string('type', 45)->nullable()->comment('Types are: COUNTRY, STATE, DISTRICT, TALUKA, MANDAL, VILLAGE');
            $table->integer('parent_type')->nullable();
            $table->string('name', 50)->nullable();
            $table->string('description', 15)->nullable();
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
        Schema::dropIfExists('locations');
    }
}
