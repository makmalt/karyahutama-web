<?php

namespace Database\Factories;

use App\Models\Barang;
use App\Models\Kategori;
use Illuminate\Database\Eloquent\Factories\Factory;

class BarangFactory extends Factory
{
    protected $model = Barang::class;

    public function definition()
    {
        return [
            'nama_barang' => $this->faker->unique()->words(3, true),
            'deskripsi' => $this->faker->sentence(),
            'barcode' => $this->faker->unique()->ean13(),
            'harga' => $this->faker->numberBetween(10000, 1000000),
            'stok_tersedia' => $this->faker->numberBetween(0, 100),
            'sku_id' => $this->faker->unique()->bothify('SKU-####-????'),
            'hargaBeli' => $this->faker->numberBetween(5000, 500000),
            'kategori_id' => Kategori::factory(),
        ];
    }
}
