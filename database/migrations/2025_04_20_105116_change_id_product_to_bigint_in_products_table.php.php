<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Pastikan foreign key dihapus terlebih dahulu dari tabel lain yang mengarah ke id_product
        Schema::table('products', function (Blueprint $table) {
            // Ubah id_product menjadi big integer dan auto increment
            $table->unsignedBigInteger('id_product', true)->change();
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // Balik lagi ke unsignedInteger jika perlu
            $table->unsignedInteger('id_product', true)->change();
        });
    }
};
