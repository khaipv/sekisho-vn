<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCandiateTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('candiate', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->nullable()->default(NULL);
            $table->date('bith')->nullable()->default(NULL);
            $table->string('tell')->nullable()->default(NULL);
            $table->string('email')->nullable()->default(NULL);
            $table->string('university')->nullable()->default(NULL);
            $table->string('skill')->nullable()->default(NULL);
            $table->string('status')->nullable()->default(NULL);
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
        Schema::dropIfExists('candiate');
    }
}
