<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DetailProdukTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        // Mengambil data dari tabel produk
        $produkA = DB::table('produk')->where('kode_produk', 'PRD001')->first();
        $produkB = DB::table('produk')->where('kode_produk', 'PRD002')->first();
        $produkC = DB::table('produk')->where('kode_produk', 'PRD003')->first();

        // Memasukkan data ke tabel detail_produk
        DB::table('produk_detail')->insert([
            [
                'kode_produk' => $produkA->kode_produk,
                'stok' => 200,
                'merk' => 'Philips',
                'harga_beli' => 35000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode_produk' => $produkB->kode_produk,
                'stok' => 150,
                'merk' => 'Sony',
                'harga_beli' => 50000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode_produk' => $produkC->kode_produk,
                'stok' => 300,
                'merk' => 'Samsung',
                'harga_beli' => 80000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
