<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penjualan extends Model
{
    use HasFactory;

    protected $table = 'penjualan';
    protected $primaryKey = 'nomor_invoice';
    public $incrementing = false; // Karena primary key bukan integer
    protected $guarded = [];

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'id_user');
    }

    public function detailPenjualan()
    {
        return $this->hasMany(PenjualanDetail::class, 'id_penjualan');
    }
}
