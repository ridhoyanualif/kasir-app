<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::table('products', function (Blueprint $table) {
            // Ubah id_product menjadi BIGINT
            $table->unsignedBigInteger('id_product')->change();
        });
    }

    public function down() {
        Schema::table('products', function (Blueprint $table) {
            // Rollback ke tipe data sebelumnya (misalnya INT, jika sebelumnya INT)
            $table->unsignedInteger('id_product')->change();
        });
    }
};
