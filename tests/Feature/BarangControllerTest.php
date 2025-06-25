<?php

use App\Models\Kategori;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    // Create necessary test data
    $this->user = User::factory()->create();
    $this->kategori = Kategori::factory()->create();
    $this->supplier = Supplier::factory()->create();
});

test('Tambah Data Tidak dengan Gambar', function () {
    $response = $this->actingAs($this->user)->post(route('barang.store'), [
        'nama_barang' => 'Cat Avian Putih',
        'deskripsi' => 'Cat tembok putih',
        'harga' => 50000,
        'stok_tersedia' => 10,
        'sku_id' => 'AVI-001',
        'hargaBeli' => 80000,
        'kategori_id' => $this->kategori->id,
        'supplier_id' => $this->supplier->id
    ]);

    $response->assertRedirect(route('barang.index'));
    $response->assertSessionHas('success');

    $this->assertDatabaseHas('barangs', [
        'nama_barang' => 'Cat Avian Putih',
        'sku_id' => 'AVI-001',
        'harga' => 50000,
        'stok_tersedia' => 10
    ]);
    print "Berhasil menambahkan data barang tanpa gambar ke database\n";
});

test('Menambahkan data barang dengan data tidak valid', function () {
    $response = $this->actingAs($this->user)->post(route('barang.store'), [
        'nama_barang' => '',
        'harga' => -100,
        'stok_tersedia' => -5,
        'hargaBeli' => -80000,
        'kategori_id' => 999 // Non-existent kategori
    ]);

    $response->assertSessionHasErrors(['nama_barang', 'harga', 'stok_tersedia', 'hargaBeli', 'kategori_id']);
    print "Berhasil menolak data barang dengan data tidak valid\n";
});

test('Tambah Data barang dengan gambar', function () {
    Storage::fake('public');

    $response = $this->actingAs($this->user)->post(route('barang.store'), [
        'nama_barang' => 'Cat Avian Merah',
        'deskripsi' => 'Deskripsi tembok putih',
        'harga' => 50000,
        'stok_tersedia' => 10,
        'sku_id' => 'avi-002',
        'hargaBeli' => 40000,
        'kategori_id' => $this->kategori->id,
        'supplier_id' => $this->supplier->id,
        'image' => UploadedFile::fake()->create('test.jpg', 100, 'image/jpeg'),
    ]);

    $response->assertRedirect(route('barang.index'));
    $response->assertSessionHas('success', 'Barang "Cat Avian Merah" berhasil ditambahkan');

    $this->assertDatabaseHas('barangs', [
        'nama_barang' => 'Cat Avian Merah',
        'deskripsi' => 'Deskripsi tembok putih',
        'harga' => 50000,
        'stok_tersedia' => 10,
        'sku_id' => 'avi-002',
        'hargaBeli' => 40000,
        'kategori_id' => $this->kategori->id,
    ]);
    print "Berhasil menambahkan data barang beserta gambar ke database\n";
});
