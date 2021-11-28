<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('VNo')->nullable();
            $table->string('Vtype')->nullable();
            $table->date('VDate')->nullable();
            $table->string('COAID');
            $table->string('Narration')->nullable();
            $table->decimal('Debit',18,2)->nullable();
            $table->decimal('Credit',18,2)->nullable();
            $table->string('IsPosted')->nullable();
            $table->unsignedBigInteger('company_id')->nullable();
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('updated_by');
            $table->tinyInteger('IsAppove')->default(0);
            $table->timestamps();
            $table->foreign('COAID')->references('HeadCode')->on('accounts');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transactions');
    }
}
