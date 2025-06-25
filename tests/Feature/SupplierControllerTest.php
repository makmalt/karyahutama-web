<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Supplier;
use App\Models\Kategori;
use Illuminate\Foundation\Testing\RefreshDatabase;


class SupplierControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $kategori;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->kategori = Kategori::factory()->create();
    }

    public function test_tambah_data_supplier_baru()
    {
        $response = $this->actingAs($this->user)->post(route('supplier.store'), [
            'nama_supplier' => 'Avian',
            'alamat' => 'Jl. Test No. 123',
            'kontak' => '081234567890',
            'kategori_id' => $this->kategori->id
        ]);

        $response->assertRedirect(route('supplier.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('suppliers', [
            'nama_supplier' => 'Avian',
            'alamat' => 'Jl. Test No. 123',
            'kontak' => '081234567890',
            'kategori_id' => $this->kategori->id
        ]);
        print "Berhasil menambahkan data supplier ke database\n";
    }

    public function test_tambah_data_supplier_dengan_data_tidak_valid()
    {
        $response = $this->actingAs($this->user)->post(route('supplier.store'), [
            'nama_supplier' => '',
            'alamat' => '',
            'kontak' => '',
        ]);

        $response->assertSessionHasErrors([
            'nama_supplier',
            'alamat',
            'kontak',
        ]);
        print "Berhasil menolak data supplier dengan data tidak valid\n";
    }
}
