<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProdukDetail extends Model
{
    use HasFactory;

    protected $table = 'produk_detail'; // Nama tabel
    protected $primaryKey = 'id_produk'; // Primary key
    public $incrementing = true; // Primary key auto-increment

    protected $fillable = [
        'id_produk',
        'kode_produk',
        'stok',
        'merk',
        'harga_beli_produk',
        'created_at',
        'updated_at',
    ];

    /**
     * Relasi ke model Produk
     */
    public function produk()
    {
        return $this->belongsTo(Produk::class, 'kode_produk', 'kode_produk');
    }
}
