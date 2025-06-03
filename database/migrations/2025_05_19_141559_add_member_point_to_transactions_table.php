<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->unsignedInteger('fid_member')->nullable()->after('user_id');
            $table->integer('point')->default(0)->after('fid_member');
            $table->integer('point_after')->default(0)->after('point');

            // Tambahan kolom baru
            $table->string('cut', 255)->nullable()->after('point_after');
            $table->string('total_price_after', 255)->nullable()->after('cut');

            // Foreign key
            $table->foreign('fid_member')->references('id_member')->on('members')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropForeign(['fid_member']);
            $table->dropColumn([
                'fid_member',
                'point',
                'point_after',
                'cut',
                'total_price_after'
            ]);
        });
    }
};
