<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Users extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id('usr_id');

            $table->integer('org_id')->nullable();
            $table->string('user_name', 45); 
            $table->unsignedBigInteger('business_unit_type')->nullable();      
          
            $table->foreign('business_unit_type')->references('org_id')->on('organizations')->index('usr_bu_fk')->comment('Organization is a general mapping of users.\nBusiness Unit ID is a specific organization unit mapping.');

            $table->string('email', 75)->unique()->nullable();
            $table->string('mobile_number', 45)->nullable();
            $table->string('user_password', 100)->nullable();
            $table->string('user_image_url', 150)->nullable();
            $table->tinyInteger('is_active')->nullable();
            $table->string('address', 150)->nullable();
            $table->string('country', 45)->nullable();
            $table->string('state', 45)->nullable();
            $table->string('district', 45)->nullable();
            $table->string('taluka', 45)->nullable();
            $table->string('village', 45)->nullable();
            $table->string('zipcode', 45)->nullable();  
            $table->string('token', 100)->nullable();
            $table->integer('create_userid')->nullable();
            $table->integer('update_userid')->nullable();
            $table->timestamp('email_verified_at')->nullable();            
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
