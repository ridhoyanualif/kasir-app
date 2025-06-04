<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('discounts', function (Blueprint $table) {
            //
            $table->dateTime('start_datetime')->nullable()->after('cut');
            $table->dateTime('end_datetime')->nullable()->after('start_datetime');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('discounts', function (Blueprint $table) {
            //
            $table->dropColumn(['start_datetime', 'end_datetime']);
        });
    }
};
