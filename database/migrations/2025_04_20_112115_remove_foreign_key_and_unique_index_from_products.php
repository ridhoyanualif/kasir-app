<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveForeignKeyAndUniqueIndexFromProducts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Hapus FOREIGN KEY constraint pada fid_category
        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign('products_ibfk_1'); // Menyesuaikan dengan nama constraint
            $table->dropUnique('products_fid_category_unique'); // Menghapus unique index pada fid_category
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Menambahkan kembali FOREIGN KEY jika ingin memulihkan perubahan
        Schema::table('products', function (Blueprint $table) {
            $table->foreign('fid_category')->references('id_category')->on('categories')->onDelete('cascade')->onUpdate('cascade');
            $table->unique('fid_category'); // Menambahkan kembali unique index pada fid_category
        });
    }
}
