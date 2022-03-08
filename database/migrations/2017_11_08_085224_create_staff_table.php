<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStaffTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       Schema::create('staff', function (Blueprint $table) {
           $table->increments('id');
            $table->string('name')->nullable()->default(NULL);
            $table->date('bith')->nullable()->default(NULL);
            $table->string('tell')->nullable()->default(NULL);
            $table->string('email')->nullable()->default(NULL);
            $table->string('company_id')->nullable()->default(NULL);
            $table->string('division_id')->nullable()->default(NULL);
            $table->string('level')->nullable()->default(NULL);
            $table->string('title')->nullable()->default(NULL);
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
        Schema::dropIfExists('staff');
    }
}
