<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Transaksi extends Model
{
    use HasFactory;

    protected $table = 'transaksi'; 
    protected $fillable = ['no_transaksi', 'tgl_transaksi', 'grand_total', 'uang_pembayaran', 'uang_kembalian'];

    public function barangTransaksi()
    {
        return $this->hasMany(BarangTransaksi::class, 'transaksi_id');
    }

    protected $casts = [
        'tgl_transaksi' => 'datetime',
    ];
}
