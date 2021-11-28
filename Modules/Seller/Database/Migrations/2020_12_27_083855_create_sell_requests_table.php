<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSellRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sell_requests', function (Blueprint $table) {
            $table->id();
            $table->string('req_id')->nullable();
            $table->date('del_date')->nullable();
            $table->date('v_date');
            $table->decimal('amount',18,2);
            $table->decimal('discount',18,2)->default(0);
            $table->decimal('del_amount',18,2)->nullable();
            $table->decimal('del_discount',18,2)->nullable();
            $table->integer('sale_disc')->nullable();
            $table->integer('sale_discount_overwrite')->nullable();
            $table->unsignedBigInteger('company_id');
            $table->unsignedBigInteger('seller_id');
            $table->unsignedBigInteger('customer_id');
            $table->boolean('is_delivered')->default('0');
            $table->boolean('is_approved')->default('0');
            $table->boolean('is_rejected')->default(false);
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->string('voucher_no')->nullable();
            $table->string('pname')->nullable();
            $table->string('receiver')->nullable();
            $table->string('dco_code')->nullable();
            $table->string('phn_no')->nullable();
            $table->string('transp_name')->nullable();
            $table->string('deliv_pname')->nullable();
            $table->string('remarks')->nullable(); 
            $table->string('gift')->nullable(); 
            $table->integer('fully_delivered')->default(0); 
            $table->integer('send_bill')->default(0);
            $table->integer('due_amount')->nullable();
            $table->integer('update_by')->nullable();
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            $table->foreign('seller_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
            $table->foreign('approved_by')->references('id')->on('users');
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
        Schema::dropIfExists('sell_requests');
    }
}
