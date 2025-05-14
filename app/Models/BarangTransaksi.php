<?php

namespace App\Models;

use App\Services\BarangTransaksiServices;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
class BarangTransaksi extends Model
{
    use HasFactory;

    protected $table = 'barang_transaksis';

    protected $fillable = [
        'barang_id',
        'transaksi_id',
        'harga_barang',
        'quantity',
        'total_harga'
    ];

    public function barang()
    {
        return $this->belongsTo(Barang::class);
    }

    public function transaksi()
    {
        return $this->belongsTo(Transaksi::class);
    }

    protected static function booted()
    {
        static::created(function ($barangTransaksi) {
            app(BarangTransaksiServices::class)->reduceStock(
                $barangTransaksi->barang_id,
                $barangTransaksi->quantity
            );
        });

        static::deleted(function ($barangTransaksi) {
            Log::info('BarangTransaksi deleted:', [
                'barang_id' => $barangTransaksi->barang_id,
                'quantity' => $barangTransaksi->quantity
            ]);

            app(BarangTransaksiServices::class)->addStock(
                $barangTransaksi->barang_id,
                $barangTransaksi->quantity
            );
        });
    }
}

