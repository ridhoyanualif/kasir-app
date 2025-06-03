<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->integer('id', 11)->autoIncrement()->change();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('total_price', 255);
            $table->string('cash', 255);
            $table->string('change', 255);
            $table->timestamp('transaction_date')->useCurrent();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('transactions');
    }
};
