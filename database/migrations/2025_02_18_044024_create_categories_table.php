<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->increments('id_category'); // auto-increment
            $table->string('name', 255);
        });
    }

    public function down()
    {
        Schema::dropIfExists('categories');
    }
};

