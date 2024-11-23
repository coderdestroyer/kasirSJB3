<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class BuatProdukTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('produk', function (Blueprint $table) {
            $table->string('kode_produk', 20)->primary(); // Kode produk sebagai primary key
            $table->unsignedInteger('id_kategori');
            $table->string('nama_produk')->unique();
            $table->integer('harga_jual');
            $table->timestamps();

            // Foreign key ke tabel kategori
            $table->foreign('id_kategori')
                  ->references('id_kategori')
                  ->on('kategori')
                  ->onUpdate('cascade')  // Jika id_kategori berubah, update di tabel produk
                  ->onDelete('restrict'); // Jika ada data produk terkait, tidak bisa menghapus kategori
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('produk');
    }
}
