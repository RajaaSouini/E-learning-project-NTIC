<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProgressionsTable extends Migration
{
    public function up()
    {
        Schema::create('progressions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('course_id');
            $table->unsignedBigInteger('formation_id')->nullable();
            $table->boolean('completed')->default(false);
            $table->integer('watch_time')->default(0); // en secondes
            $table->timestamps();

            $table->unique(['user_id', 'course_id']); // un user ne peut marquer qu'une fois
        });
    }

    public function down()
    {
        Schema::dropIfExists('progressions');
    }
}