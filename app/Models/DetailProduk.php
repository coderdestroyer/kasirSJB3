<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailProduk extends Model
{
    use HasFactory;

    protected $table = 'produk_detail';  // Pastikan ini sesuai dengan nama tabel

    protected $fillable = [
        'id_produk', 
        'stok', 
        'merk', 
        'harga_beli'
    ];

    protected $primaryKey = 'id_produk_detail';

    public function produk()
    {
        return $this->belongsTo(Produk::class, 'id_produk', 'id_produk');
    }
}
