<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->bigInteger('id', false, true)->primary();
            $table->string('invoice', 255)->nullable()->unique();
            $table->bigInteger('user_id', false, true);
            $table->integer('fid_member', false, true)->nullable();
            $table->integer('point')->default(0);
            $table->integer('point_after')->default(0);
            $table->string('cut', 255)->nullable();
            $table->string('total_price_after', 255)->nullable();
            $table->string('total_price', 255);
            $table->string('cash', 255);
            $table->string('change', 255);
            $table->timestamp('transaction_date')->useCurrent();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('fid_member')->references('id_member')->on('members')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
