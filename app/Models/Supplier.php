<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Supplier extends Model
{
    use HasFactory;
    protected $table = 'suppliers';
    protected $fillable = [
        'nama_supplier',
        'alamat',
        'kontak',
    ];

    public function tagihan()
    {
        return $this->hasMany(Tagihan::class);
    }

    public function tambahStok()
    {
        return $this->hasMany(TambahStok::class);
    }

    public function barang()
    {
        return $this->hasMany(Barang::class);
    }
}
