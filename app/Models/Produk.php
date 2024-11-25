<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    use HasFactory;

    protected $table = 'produk'; // Nama tabel
    protected $primaryKey = 'kode_produk'; // Primary key
    public $incrementing = false; // Primary key bukan auto-increment
    protected $keyType = 'string'; // Primary key berupa varchar

    protected $fillable = [
        'kode_produk',
        'id_kategori',
        'nama_produk',
        'harga_jual',
        'created_at',
        'updated_at',
    ];

    /**
     * Relasi ke model ProdukDetail
     */
    public function produkDetail()
    {
        return $this->hasOne(ProdukDetail::class, 'kode_produk', 'kode_produk');
    }

    /**
     * Relasi ke model Kategori
     */
    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'id_kategori', 'id_kategori');
    }
}
