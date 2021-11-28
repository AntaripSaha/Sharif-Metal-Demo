<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('product_id');
            $table->string('product_name');
            $table->string('product_model')->nullable();
            $table->boolean('is_set')->default(0);
            $table->integer('is_head')->default(0);
            $table->string('head_code')->nullable();
            $table->string('set_id')->nullable();
            $table->string('combo_ids')->nullable();
            $table->string('product_details')->nullable();
            $table->string('image')->nullable();
            $table->decimal('price',18,2);
            $table->decimal('production_price',18,2)->nullable();
            $table->float('tax')->nullable();
            $table->unsignedBigInteger('category_id')->nullable();
            $table->unsignedBigInteger('unit_id');
            $table->unsignedBigInteger('supplier_id')->nullable();
            $table->unsignedBigInteger('company_id')->nullable();
            $table->boolean('status')->default('1');
            $table->foreign('supplier_id')->references('id')->on('suppliers')->onDelete('cascade');
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
            $table->foreign('unit_id')->references('id')->on('units')->onDelete('cascade');
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
        Schema::dropIfExists('products');
    }
}
