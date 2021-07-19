<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Organizations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('organizations', function (Blueprint $table) {
            $table->id('org_id');

            $table->unsignedBigInteger('app_id');
            $table->foreign('app_id')->references('app_id')->on('applications')->index('org_app_fk');

            $table->integer('parent_org_id')->nullable()->index('org_parent_fk');
            $table->string('org_code', 15)->nullable()->comment('Organization Code is used as a subdomain of website. This should not have any special characters to it. Only upto 10 char text.');
            $table->string('org_name', 45);
            $table->string('org_level', 45)->nullable()->comment('org level will be:\n- Management\n- Business Unit\n- Operational Unit');
            $table->string('org_description', 250)->nullable();
            $table->string('org_logo_url', 250)->nullable();
            $table->integer('org_type')->nullable();
            $table->string('org_address', 250)->nullable();
            $table->integer('org_district')->nullable();
            $table->integer('org_state')->nullable();
            $table->integer('org_country')->nullable();
            $table->integer('contact_userid')->nullable();
            $table->tinyInteger('is_super_admin')->nullable()->comment('Agribridge as an organization is known as a superadmin.');
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
        Schema::dropIfExists('organizations');
    }
}
