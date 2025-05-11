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
        Schema::table('transaksi', function (Blueprint $table) {
            //
            $table->decimal('uang_pembayaran', 15, 2)->nullable();
            $table->decimal('uang_kembalian', 15, 2)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transaksi', function (Blueprint $table) {
            //
            $table->dropColumn('uang_pembayaran');
            $table->dropColumn('uang_kembalian');
        });
    }
};
