<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('transaction_details', function (Blueprint $table) {
            // Pastikan product_id ada di tabel transaction_details
            if (!Schema::hasColumn('transaction_details', 'product_id')) {
                $table->unsignedBigInteger('product_id'); // atau sesuaikan dengan tipe id_product
            }

            // Tambahkan foreign key
            $table->foreign('product_id')
                ->references('id_product')
                ->on('products')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('transaction_details', function (Blueprint $table) {
            $table->dropForeign(['product_id']);
        });
    }
};
