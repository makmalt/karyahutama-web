<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Barang;
use App\Models\Kategori;
use App\Models\Supplier;
use App\Models\Transaksi;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TransaksiControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $barang;
    protected $kategori;
    protected $supplier;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->kategori = Kategori::factory()->create();
        $this->supplier = Supplier::factory()->create();

        // Buat data barang untuk testing
        $this->barang = Barang::create([
            'nama_barang' => 'Test Barang',
            'deskripsi' => 'Deskripsi test barang',
            'barcode' => '123456789',
            'harga' => 100000,
            'stok_tersedia' => 10,
            'sku_id' => 'SKU123',
            'hargaBeli' => 80000,
            'kategori_id' => $this->kategori->id,
            'supplier_id' => $this->supplier->id
        ]);
    }

    public function test_tambah_transaksi()
    {
        $response = $this->actingAs($this->user)->post(route('transaksi.store'), [
            'no_transaksi' => 'TRX-' . now()->format('dmY') . '-' . strtoupper(uniqid()),
            'tgl_transaksi' => now()->format('Y-m-d'),
            'grand_total' => 200000,
            'uang_pembayaran' => 250000,
            'uang_kembalian' => 50000,
            'barang_id' => [$this->barang->id],
            'quantity' => [2],
            'harga_barang' => [100000],
            'total_harga' => [200000]
        ]);

        $response->assertRedirect(route('transaksi.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('transaksi', [
            'grand_total' => 200000,
            'uang_pembayaran' => 250000,
            'uang_kembalian' => 50000
        ]);

        $this->assertDatabaseHas('barang_transaksis', [
            'barang_id' => $this->barang->id,
            'quantity' => 2,
            'harga_barang' => 100000,
            'total_harga' => 200000
        ]);

        print "Berhasil menambahkan transaksi baru ke database\n";
    }

    public function test_data_tidak_valid()
    {
        $response = $this->actingAs($this->user)->post(route('transaksi.store'), [
            'no_transaksi' => '',
            'tgl_transaksi' => '',
            'grand_total' => '',
            'uang_pembayaran' => '',
            'uang_kembalian' => '',
            'barang_id' => [],
            'quantity' => [],
            'harga_barang' => [],
            'total_harga' => []
        ]);

        $response->assertSessionHasErrors([
            'no_transaksi',
            'tgl_transaksi',
            'grand_total'
        ]);
        print "Berhasil menolak transaksi dengan data tidak valid\n";
    }
}
