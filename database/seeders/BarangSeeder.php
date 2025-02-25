<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BarangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            // Supplier 1 (5 barang)
            ['barang_id' => 1, 'kategori_id' => 1, 'barang_kode' => 'BRG-001', 'barang_nama' => 'Laptop Acer Aspire', 'harga_beli' => 7500000, 'harga_jual' => 8500000],
            ['barang_id' => 2, 'kategori_id' => 1, 'barang_kode' => 'BRG-002', 'barang_nama' => 'Smartphone Samsung A51', 'harga_beli' => 3200000, 'harga_jual' => 3800000],
            ['barang_id' => 3, 'kategori_id' => 4, 'barang_kode' => 'BRG-003', 'barang_nama' => 'Blender Philips', 'harga_beli' => 450000, 'harga_jual' => 550000],
            ['barang_id' => 4, 'kategori_id' => 4, 'barang_kode' => 'BRG-004', 'barang_nama' => 'Rice Cooker Cosmos', 'harga_beli' => 320000, 'harga_jual' => 399000],
            ['barang_id' => 5, 'kategori_id' => 5, 'barang_kode' => 'BRG-005', 'barang_nama' => 'Oli Mobil Shell', 'harga_beli' => 85000, 'harga_jual' => 110000],
            
            // Supplier 2 (5 barang)
            ['barang_id' => 6, 'kategori_id' => 2, 'barang_kode' => 'BRG-006', 'barang_nama' => 'Kemeja Pria', 'harga_beli' => 120000, 'harga_jual' => 189000],
            ['barang_id' => 7, 'kategori_id' => 2, 'barang_kode' => 'BRG-007', 'barang_nama' => 'Celana Jeans', 'harga_beli' => 150000, 'harga_jual' => 225000],
            ['barang_id' => 8, 'kategori_id' => 2, 'barang_kode' => 'BRG-008', 'barang_nama' => 'Jaket Bomber', 'harga_beli' => 200000, 'harga_jual' => 299000],
            ['barang_id' => 9, 'kategori_id' => 1, 'barang_kode' => 'BRG-009', 'barang_nama' => 'Speaker Bluetooth', 'harga_beli' => 175000, 'harga_jual' => 250000],
            ['barang_id' => 10, 'kategori_id' => 5, 'barang_kode' => 'BRG-010', 'barang_nama' => 'Lampu LED Motor', 'harga_beli' => 45000, 'harga_jual' => 75000],
            
            // Supplier 3 (5 barang)
            ['barang_id' => 11, 'kategori_id' => 3, 'barang_kode' => 'BRG-011', 'barang_nama' => 'Mie Instan 1 Dus', 'harga_beli' => 85000, 'harga_jual' => 98000],
            ['barang_id' => 12, 'kategori_id' => 3, 'barang_kode' => 'BRG-012', 'barang_nama' => 'Kopi Bubuk Premium', 'harga_beli' => 25000, 'harga_jual' => 35000],
            ['barang_id' => 13, 'kategori_id' => 3, 'barang_kode' => 'BRG-013', 'barang_nama' => 'Coklat Batang', 'harga_beli' => 12000, 'harga_jual' => 18000],
            ['barang_id' => 14, 'kategori_id' => 4, 'barang_kode' => 'BRG-014', 'barang_nama' => 'Sapu Lantai', 'harga_beli' => 35000, 'harga_jual' => 45000],
            ['barang_id' => 15, 'kategori_id' => 1, 'barang_kode' => 'BRG-015', 'barang_nama' => 'Earphone Wireless', 'harga_beli' => 120000, 'harga_jual' => 180000],
        ];
        DB::table('m_barang')->insert($data);
    }
}
