<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class KategoriSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        DB::table('kategori')->insert([
            [
                'id_kategori' => 1,
                'nama_kategori' => 'Kategori1',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_kategori' => 2,
                'nama_kategori' => 'Kategori2',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_kategori' => 3,
                'nama_kategori' => 'Kategori3',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
