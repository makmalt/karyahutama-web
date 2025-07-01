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
        Schema::table('tambah_stoks', function (Blueprint $table) {
            $table->decimal('harga_satuan', 15, 2)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tambah_stoks', function (Blueprint $table) {
            $table->decimal('harga_satuan', 15, 2)->nullable(false)->change();
        });
    }
};
