<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class BuatPenjualanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('penjualan', function (Blueprint $table) {
            $table->string('nomor_invoice')->primary(); // Primary key
            $table->unsignedBigInteger('id_user'); // Foreign key ke tabel users
            $table->bigInteger('id_kasir'); // Foreign key ke tabel kasir
            $table->date('tanggal_penjualan'); // Tanggal penjualan
            $table->timestamps(); // created_at dan updated_at

            // Relasi foreign key ke tabel users
            $table->foreign('id_user')->references('id')->on('users')->onDelete('cascade');

            // Relasi foreign key ke tabel kasir
            $table->foreign('id_kasir')->references('id_kasir')->on('kasir')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('penjualan');
    }
}
