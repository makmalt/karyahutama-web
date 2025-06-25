<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Barang;
use App\Models\Kategori;
use App\Models\Supplier;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BarangShowTest extends TestCase
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
            'nama_barang' => 'Besi',
            'deskripsi' => 'Besi',
            'harga' => 100000,
            'stok_tersedia' => 10,
            'sku_id' => 'BESI-001',
            'hargaBeli' => 80000,
            'kategori_id' => $this->kategori->id,
            'supplier_id' => $this->supplier->id
        ]);
    }

    public function test_menampilkan_detail_barang()
    {
        $response = $this->actingAs($this->user)->get(route('barang.show', $this->barang->id));

        $response->assertStatus(200);
        $response->assertViewIs('barang.show');
        $response->assertViewHas('barang');
        $response->assertViewHas('suppliers');
        $response->assertViewHas('tambahStok');

        $response->assertSee($this->barang->nama_barang);
        $response->assertSee($this->barang->deskripsi);
        $response->assertSee('Rp ' . number_format($this->barang->harga, 0, ',', '.'));
        $response->assertSee($this->barang->stok_tersedia);
        $response->assertSee($this->barang->sku_id);

        print "Berhasil menampilkan detail barang\n";
        print "\nData Barang:\n";
        print "Nama Barang: " . $this->barang->nama_barang . "\n";
        print "Deskripsi: " . $this->barang->deskripsi . "\n";
        print "Harga: Rp " . number_format($this->barang->harga, 0, ',', '.') . "\n";
        print "Stok Tersedia: " . $this->barang->stok_tersedia . "\n";
        print "SKU ID: " . $this->barang->sku_id . "\n";
        print "Harga Beli: Rp " . number_format($this->barang->hargaBeli, 0, ',', '.') . "\n";
        print "Kategori: " . $this->kategori->nama_kategori . "\n";
        print "Supplier: " . $this->supplier->nama_supplier . "\n";
    }
}
