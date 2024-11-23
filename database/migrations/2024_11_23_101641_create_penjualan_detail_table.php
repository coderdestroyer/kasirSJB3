<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePenjualanDetailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('penjualan_detail', function (Blueprint $table) {
            $table->increments('id_penjualan_detail'); // Primary key
            $table->string('nomor_invoice'); // Foreign key ke nomor_invoice di tabel penjualan
            $table->string('nama_produk'); // Nama produk
            $table->integer('harga_jual'); // Harga jual produk
            $table->integer('jumlah'); // Jumlah produk
            $table->timestamps(); // created_at dan updated_at

            // Relasi foreign key ke tabel penjualan
            $table->foreign('nomor_invoice')->references('nomor_invoice')->on('penjualan')->onDelete('cascade');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('penjualan_detail');
    }
}
