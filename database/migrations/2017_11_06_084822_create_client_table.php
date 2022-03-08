<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClientTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('client', function (Blueprint $table) {
           $table->increments('id');
            $table->string('code')->nullable()->default(NULL);
            $table->string('companyname');
            $table->string('representname')->nullable()->default(NULL);
            $table->string('tel')->nullable()->default(NULL);
            $table->string('url')->nullable()->default(NULL);
            $table->string('address')->nullable()->default(NULL);
            $table->string('pic_s')->nullable()->default(NULL);
            $table->string('pic')->nullable()->default(NULL);
            $table->string('rate')->nullable()->default(NULL);
            $table->string('introduce')->nullable()->default(NULL);
            $table->string('avertise')->nullable()->default(NULL);
            $table->datetime('visitdate')->nullable()->default(NULL);
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
        Schema::dropIfExists('client');
    }
}
