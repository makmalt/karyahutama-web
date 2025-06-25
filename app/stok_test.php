<?php
date_default_timezone_set('Asia/Jakarta');

require_once __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Services\StokServices;
use App\Models\TambahStok;

$stok = new StokServices();

// Test fungsi totalHarga
echo "== Test totalHarga() ==\n";
$total = $stok->totalHarga(10, 5000);
echo "Expected: 50000 | Got: $total\n\n";

// Test fungsi awalStok
echo "== Test awalStok() ==\n";
$data = $stok->awalStok(1, 2, 10, 5000);
print_r($data);

echo "== Test getTambahStok() ==\n";
$data = $stok->getTambahStok(6);
print_r($data->toArray());
