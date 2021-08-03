<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrganizationStructureTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('organization_structure', function (Blueprint $table) {
            $table->id('ogs_id');
             $table->bigInteger('org_id')->unsigned()->index();
             $table->foreign('org_id')->references('org_id')->on('organizations')->index('org_id_fk');
            $table->integer('parent_ogs_id')->nullable();
            $table->string('business_unit_type', 45)->nullable();
            $table->string('ogs_code', 45)->nullable();
            $table->string('ogs_description', 45)->nullable();
            $table->string('ogs_icon_url', 100)->nullable();
            $table->integer('created_userid')->nullable();
            $table->integer('updated_userid')->nullable();
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
        Schema::dropIfExists('organization_structure');
    }
}
