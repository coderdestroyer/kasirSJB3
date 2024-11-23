<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProdukDetailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('produk_detail', function (Blueprint $table) {
            $table->increments('id_produk_detail'); // Primary key
            $table->string('kode_produk', 20); // Kode produk sebagai foreign key
            $table->integer('stok'); // Stok produk
            $table->string('merk')->nullable(); // Merk produk
            $table->integer('harga_beli'); // Harga beli produk
            $table->timestamps(); // created_at, updated_at

            // Foreign key ke tabel produk
            $table->foreign('kode_produk')
                  ->references('kode_produk') // Mengacu pada kolom kode_produk di tabel produk
                  ->on('produk')
                  ->onUpdate('cascade') // Mengupdate kode_produk secara otomatis jika ada perubahan
                  ->onDelete('restrict'); // Tidak bisa menghapus produk yang memiliki detail
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('produk_detail');
    }
}
