<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * 
     */
    public function up(): void
    {
        Schema::table('transaksi_rentals', function (Blueprint $table) {
            $table->dateTime('tgl_peminjaman')->change();
            $table->dateTime('tgl_pengembalian')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * 
     */
    public function down(): void
    {
        Schema::table('transaksi_rentals', function (Blueprint $table) {
            $table->date('tgl_peminjaman')->change();
            $table->date('tgl_pengembalian')->change();
        });
    }
};
