<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class BuatPembelianTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pembelian', function (Blueprint $table) {
            $table->increments('id_pembelian'); // Primary key
            $table->date('tanggal_pembelian'); // Kolom untuk tanggal pembelian
            $table->unsignedInteger('id_supplier'); // Foreign key ke tabel supplier
            $table->timestamps(); // created_at dan updated_at

            // Relasi foreign key ke tabel supplier
            $table->foreign('id_supplier')->references('id_supplier')->on('supplier')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pembelian');
    }
}
