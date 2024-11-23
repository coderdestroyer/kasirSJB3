<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKasirTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kasir', function (Blueprint $table) {
            $table->bigIncrements('id_kasir'); // Primary key
            $table->unsignedBigInteger('id_user'); // Foreign key ke tabel user
            $table->string('nomor_hp'); // Nomor HP kasir
            $table->text('alamat'); // Alamat kasir
            $table->timestamps(); // created_at, updated_at

            // Foreign key ke tabel user
            $table->foreign('id_user')
                  ->references('id') // Mengacu pada id_user di tabel user
                  ->on('users') // Tabel yang menjadi referensi
                  ->onUpdate('cascade') // Mengupdate id_user secara otomatis jika ada perubahan
                  ->onDelete('restrict'); // Tidak bisa menghapus kasir yang terkait dengan user
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('kasir');
    }
}
