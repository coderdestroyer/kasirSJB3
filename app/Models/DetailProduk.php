<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailProduk extends Model
{
    use HasFactory;

    protected $table = 'detail_produk';  // Pastikan ini sesuai dengan nama tabel

    protected $fillable = [
        'id_produk', 
        'stok_produk', 
        'merk', 
        'harga_beli_produk'
    ];

    protected $primaryKey = 'id_detail_produk';

    public function produk()
    {
        return $this->belongsTo(Produk::class, 'id_produk', 'id_produk');
    }
}
