<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id('id_product', false, true)->primary();
            $table->string('barcode', 255)->nullable()->unique();
            $table->string('name', 255);
            $table->string('photo', 255)->nullable();
            $table->date('expired_date')->nullable();
            $table->integer('stock');
            $table->string('modal', 255);
            $table->string('selling_price', 255);
            $table->string('selling_price_before', 255)->nullable();
            $table->string('profit', 255);
            $table->unsignedBigInteger('fid_category');
            $table->unsignedBigInteger('fid_discount', false, true)->nullable();
            $table->string('description', 255)->nullable();
            $table->timestamps();

            $table->foreign('fid_category')->references('id_category')->on('categories')->onUpdate('cascade');
            $table->foreign('fid_discount')->references('id')->on('discounts')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
