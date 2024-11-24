<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TambahIdKasirPenjualanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('penjualan', function (Blueprint $table) {
            $table->unsignedBigInteger('id_kasir')->after('id_user')->nullable(); // Tambahkan kolom id_kasir setelah id_user
            $table->foreign('id_kasir')->references('id_kasir')->on('kasir')->onDelete('cascade'); // Set foreign key ke tabel kasir
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('penjualan', function (Blueprint $table) {
            $table->dropForeign(['id_kasir']); // Hapus foreign key
            $table->dropColumn('id_kasir');   // Hapus kolom id_kasir
        });
    }
}
