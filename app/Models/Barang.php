<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    use HasFactory;

    protected $table = 'barangs';
    protected $fillable = [
        'nama_barang',
        'deskripsi',
        'image',
        'barcode',
        'harga',
        'stok_tersedia',
        'sku_id',
        'hargaBeli',
        'kategori_id'
    ];

    public function kategori()
    {
        return $this->belongsTo(Kategori::class);
    }

    public function transaksi()
    {
        return $this->hasMany(BarangTransaksi::class);
    }

    public function tambahStok()
    {
        return $this->hasMany(TambahStok::class);
    }

    public function barangTransaksi()
    {
        return $this->hasMany(BarangTransaksi::class);
    }

    protected static function booted()
    {
        static::deleting(function ($barang) {
            if ($barang->barangTransaksi()->exists()) {
                throw new \Exception("Barang tidak bisa dihapus karena masih digunakan dalam transaksi.");
            }
        });
    }
}
