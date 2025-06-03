<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::table('transaction_details', function (Blueprint $table) {
            // Jika sebelumnya sudah ada foreign key, hapus terlebih dahulu
            

            // Pastikan transaction_id adalah unsignedBigInteger
            $table->unsignedBigInteger('transaction_id')->change();

            // Tambahkan kembali foreign key dengan id di tabel transactions
            $table->foreign('transaction_id')->references('id')->on('transactions')->onDelete('cascade');
        });
    }

    
};
