<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVideosTable extends Migration
{
    public function up()
    {
        Schema::create('videos', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('filename');
            $table->string('path');
            $table->string('url');
            $table->integer('size')->default(0);
            $table->unsignedBigInteger('course_id')->nullable();
            $table->unsignedBigInteger('professor_id');
            $table->enum('status', ['uploading', 'ready', 'error'])->default('uploading');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('videos');
    }
}