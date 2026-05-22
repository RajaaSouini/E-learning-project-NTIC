<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCoursesTable extends Migration
{
    public function up()
    {
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('slug')->unique();
            $table->string('technology');
            $table->enum('level', ['debutant', 'intermediaire', 'avance']);
            $table->integer('duration')->default(0); // en minutes
            $table->string('video_url')->nullable();
            $table->string('thumbnail')->nullable();
            $table->unsignedBigInteger('professor_id');
            $table->enum('status', ['brouillon', 'publie'])->default('brouillon');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('courses');
    }
}