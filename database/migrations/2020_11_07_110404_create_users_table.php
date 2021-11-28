<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('user_id')->nullable();
            $table->integer('parent_id')->nullable();
            $table->string('name');
            $table->string('email');
            $table->timestamp('email_verified_at');
            $table->string('password')->nullable();
            $table->string('phone_no')->nullable();
            $table->string('address')->nullable();
            $table->unsignedBigInteger('company_id');
            $table->unsignedBigInteger('role_id');
            $table->integer('created_by');
            $table->integer('updated_by')->nullable();
            $table->integer('country_id');
            $table->integer('status')->default(0);
            $table->string('remember_token')->nullable();
            $table->timestamps();
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
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
