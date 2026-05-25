<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAbonnementsTable extends Migration
{
    public function up()
    {
        Schema::create('abonnements', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->enum('plan', ['mensuel', 'annuel']);
            $table->decimal('prix', 8, 2);
            $table->enum('statut', ['actif', 'expire', 'annule'])->default('actif');
            $table->timestamp('debut_le');
            $table->timestamp('expire_le');
            $table->timestamps();

            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('abonnements');
    }
}