<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPremiumToFormationsTable extends Migration
{
    public function up()
    {
        Schema::table('formations', function (Blueprint $table) {
            $table->boolean('is_premium')->default(false)->after('status');
        });
    }

    public function down()
    {
        Schema::table('formations', function (Blueprint $table) {
            $table->dropColumn('is_premium');
        });
    }
}