<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::table('transactions', function (Blueprint $table) {
            // Tambahkan kolom id sebagai primary key
            $table->id()->first();
        });
    }

    public function down() {
        Schema::table('transactions', function (Blueprint $table) {
            // Hapus kolom id jika di-rollback
            $table->dropColumn('id');
        });
    }
};
