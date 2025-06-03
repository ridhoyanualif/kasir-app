<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('transaction_details', function (Blueprint $table) {
            // Pastikan product_id ada sebelum menambah foreign key
            if (!Schema::hasColumn('transaction_details', 'product_id')) {
                $table->unsignedBigInteger('product_id')->after('transaction_id');
            }

            // Tambahkan foreign key ke id_product di tabel products
            $table->foreign('product_id')->references('id_product')->on('products')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('transaction_details', function (Blueprint $table) {
            $table->dropForeign(['product_id']); // Hapus foreign key jika rollback
        });
    }
};
