<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TambahStok extends Model
{
    use HasFactory;

    protected $table = 'tambah_stok';
    protected $fillable = [
        'barang_id',
        'supplier_id',
        'tgl_masuk',
        'kuantitas',
        'harga_satuan',
        'total_harga',
        'keterangan'
    ];

    protected $casts = [
        'tgl_masuk' => 'datetime'
    ];

    public function barang()
    {
        return $this->belongsTo(Barang::class);
    }
}
