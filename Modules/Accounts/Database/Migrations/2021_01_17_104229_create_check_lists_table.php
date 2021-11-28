<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCheckListsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('check_lists', function (Blueprint $table) {
            $table->id();
            $table->string('check_no');
            $table->date('VDate');
            $table->date('mat_date');
            $table->boolean('is_credited')->default('0');
            $table->string('COAID');
            $table->string('bank_name');
            $table->unsignedBigInteger('customer_id')->nullable();
            $table->unsignedBigInteger('company_id');
            $table->foreign('COAID')->references('HeadCode')->on('accounts');
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
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
        Schema::dropIfExists('check_lists');
    }
}
