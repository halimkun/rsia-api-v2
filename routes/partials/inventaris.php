<?php

use Orion\Facades\Orion;
use Illuminate\Support\Facades\Route;

Route::middleware(['claim:role,pegawai|dokter'])->prefix('inventaris')->group(function ($router) {
    Orion::resource('.', \App\Http\Controllers\Orion\InventarisController::class)->withoutBatch()->parameter('', 'no_inventaris');
    Orion::resource('barang', \App\Http\Controllers\Orion\InventarisBarangController::class)->withoutBatch()->parameter('barang', 'kode_barang');
    Orion::resource('jenis', \App\Http\Controllers\Orion\InventarisJenisController::class)->except(['search', 'restore'])->withoutBatch()->parameter('jenis', 'id_jenis');
    Orion::resource('kategori', \App\Http\Controllers\Orion\InventarisKategoriController::class)->except(['search', 'restore'])->withoutBatch();
    Orion::resource('merk', \App\Http\Controllers\Orion\InventarisMerkController::class)->except(['search', 'restore'])->withoutBatch();
    Orion::resource('produsen', \App\Http\Controllers\Orion\InventarisProdusenController::class)->except(['search', 'restore'])->withoutBatch();
    Orion::resource('ruang', \App\Http\Controllers\Orion\InventarisRuangController::class)->except(['search', 'restore'])->withoutBatch();

    // TODO : pemeliharaan data, perbaikan inventaris, permintaan perbaikan inventaris
});
