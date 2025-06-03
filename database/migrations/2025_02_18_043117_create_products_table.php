<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->increments('id_product'); // auto-increment
            $table->string('name', 255);
            $table->date('expired_date');
            $table->integer('stock');
            $table->string('modal', 255);
            $table->string('selling_price', 255);
            $table->string('profit', 255);
            $table->integer('fid_category')->unsigned();
            $table->text('description');
        });
    }

    public function down()
    {
        Schema::dropIfExists('products');
    }
};
