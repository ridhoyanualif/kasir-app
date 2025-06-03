<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeyToProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            // Menambahkan foreign key untuk fid_category
            $table->foreign('fid_category')
                ->references('id_category')
                ->on('categories')
                ->onDelete('restrict')  // Menghapus produk jika kategori dihapus
                ->onUpdate('cascade'); // Memperbarui produk jika kategori diupdate
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            // Menghapus foreign key jika rollback dilakukan
            $table->dropForeign(['fid_category']);
        });
    }
}
