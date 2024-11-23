<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class BuatPembelianDetailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pembelian_detail', function (Blueprint $table) {
            $table->increments('id_pembelian_detail'); // Primary key
            $table->unsignedInteger('id_pembelian'); // Foreign key ke tabel pembelian
            $table->string('nama_produk'); // Nama produk
            $table->integer('harga_beli'); // Harga beli produk
            $table->integer('jumlah'); // Jumlah produk
            $table->enum('status', ['LUNAS', 'UTANG']); // Status (LUNAS atau UTANG)
            $table->timestamps(); // created_at dan updated_at

            // Relasi foreign key ke tabel pembelian
            $table->foreign('id_pembelian')->references('id_pembelian')->on('pembelian')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pembelian_detail');
    }
}
