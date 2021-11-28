<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateModulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('modules', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('slug');
            $table->string('display_name');
            $table->string('display_name_singular')->nullable();           
            $table->string('display_name_plural')->nullable();           
            $table->string('model_name')->nullable();
            $table->string('icon')->nullable();
            $table->string('description')->nullable();
            $table->string('controller')->nullable();
            $table->string('generate_permissions')->nullable();
            $table->string('policy_name')->nullable();
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
        Schema::dropIfExists('modules');
    }
}
