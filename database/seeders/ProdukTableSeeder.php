<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProdukTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Seed data untuk tabel `produk`
        DB::table('produk')->insert([
            [
                'kode_produk' => 'PRD001',
                'id_kategori' => 1, // Sesuaikan dengan ID kategori yang ada
                'nama_produk' => 'Lampu LED 10W',
                'harga_jual' => 50000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode_produk' => 'PRD002',
                'id_kategori' => 1, // Sesuaikan dengan ID kategori yang ada
                'nama_produk' => 'Kabel HDMI 2M',
                'harga_jual' => 75000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode_produk' => 'PRD003',
                'id_kategori' => 1, // Sesuaikan dengan ID kategori yang ada
                'nama_produk' => 'Adaptor Charger 2A',
                'harga_jual' => 100000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // Seed data untuk tabel `produk_detail`
        DB::table('produk_detail')->insert([
            [
                'id_produk' => 1, // Sesuaikan dengan ID Produk jika ada auto-increment
                'kode_produk' => 'PRD001',
                'stok' => 100,
                'merk' => 'Philips',
                'harga_beli_produk' => 40000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_produk' => 2, // Sesuaikan dengan ID Produk jika ada auto-increment
                'kode_produk' => 'PRD002',
                'stok' => 200,
                'merk' => 'Sony',
                'harga_beli_produk' => 60000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_produk' => 3, // Sesuaikan dengan ID Produk jika ada auto-increment
                'kode_produk' => 'PRD003',
                'stok' => 150,
                'merk' => 'Samsung',
                'harga_beli_produk' => 80000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
