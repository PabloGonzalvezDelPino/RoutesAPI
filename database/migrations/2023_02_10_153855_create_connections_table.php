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
        Schema::dropIfExists('connections');
        Schema::create('connections', function (Blueprint $table) {
            $table->id();
            $table->text('name');
            $table->integer('distance');
            $table->integer('speed');
            $table->unsignedBigInteger('origin');
            $table->foreign('origin')->references('id')->on('nodes');
            $table->unsignedBigInteger('destination');
            $table->foreign('destination')->references('id')->on('nodes');
            $table->tinyInteger('unidirectional');
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
        Schema::dropIfExists('connections');
    }
};
