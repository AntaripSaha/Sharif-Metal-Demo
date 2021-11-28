<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('companies', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('company_no');
            $table->string('phone_no');
            $table->integer('country_id');
            $table->integer('status')->default('0');
            $table->integer('parent_id');
            $table->string('phone_code');
            $table->string('address');
            $table->integer('created_by');
            $table->integer('updated_by')->nullable();
            $table->string('city');
            $table->string('postal_code');
            $table->string('logo')->nullable();
            $table->string('logo_sm')->nullable();
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
        Schema::dropIfExists('companies');
    }
}
