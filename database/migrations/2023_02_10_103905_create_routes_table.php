<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('routes', function (Blueprint $table) {
            $table->id();
            $table->text('name');
            $table->unsignedBigInteger('start_id');
            $table->foreign('start_id')->references('id')->on('nodes');
            $table->unsignedBigInteger('destination_id');
            $table->foreign('destination_id')->references('id')->on('nodes');
            $table->enum('type',['unidireccional','bidireccional']);
            $table->float('distance');
            $table->float('speed');
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
        Schema::dropIfExists('routes');
    }
};
