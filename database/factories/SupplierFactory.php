<?php

namespace Database\Factories;

use App\Models\Supplier;
use Illuminate\Database\Eloquent\Factories\Factory;

class SupplierFactory extends Factory
{
    protected $model = Supplier::class;

    public function definition()
    {
        return [
            'nama_supplier' => $this->faker->company(),
            'alamat' => $this->faker->address(),
            'kontak' => $this->faker->phoneNumber(),
        ];
    }
}
