<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PenjualanDetailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            // Penjualan 1 (3 items)
            ['detail_id' => 1, 'penjualan_id' => 1, 'barang_id' => 1, 'harga' => 8500000, 'jumlah' => 1],
            ['detail_id' => 2, 'penjualan_id' => 1, 'barang_id' => 2, 'harga' => 3800000, 'jumlah' => 1],
            ['detail_id' => 3, 'penjualan_id' => 1, 'barang_id' => 3, 'harga' => 550000, 'jumlah' => 2],
            
            // Penjualan 2 (3 items)
            ['detail_id' => 4, 'penjualan_id' => 2, 'barang_id' => 4, 'harga' => 399000, 'jumlah' => 1],
            ['detail_id' => 5, 'penjualan_id' => 2, 'barang_id' => 5, 'harga' => 110000, 'jumlah' => 3],
            ['detail_id' => 6, 'penjualan_id' => 2, 'barang_id' => 6, 'harga' => 189000, 'jumlah' => 2],
            
            // Penjualan 3 (3 items)
            ['detail_id' => 7, 'penjualan_id' => 3, 'barang_id' => 7, 'harga' => 225000, 'jumlah' => 1],
            ['detail_id' => 8, 'penjualan_id' => 3, 'barang_id' => 8, 'harga' => 299000, 'jumlah' => 1],
            ['detail_id' => 9, 'penjualan_id' => 3, 'barang_id' => 9, 'harga' => 250000, 'jumlah' => 2],
            
            // Penjualan 4 (3 items)
            ['detail_id' => 10, 'penjualan_id' => 4, 'barang_id' => 10, 'harga' => 75000, 'jumlah' => 4],
            ['detail_id' => 11, 'penjualan_id' => 4, 'barang_id' => 11, 'harga' => 98000, 'jumlah' => 2],
            ['detail_id' => 12, 'penjualan_id' => 4, 'barang_id' => 12, 'harga' => 35000, 'jumlah' => 5],
            
            // Penjualan 5 (3 items)
            ['detail_id' => 13, 'penjualan_id' => 5, 'barang_id' => 13, 'harga' => 18000, 'jumlah' => 10],
            ['detail_id' => 14, 'penjualan_id' => 5, 'barang_id' => 14, 'harga' => 45000, 'jumlah' => 3],
            ['detail_id' => 15, 'penjualan_id' => 5, 'barang_id' => 15, 'harga' => 180000, 'jumlah' => 1],
            
            // Penjualan 6 (3 items)
            ['detail_id' => 16, 'penjualan_id' => 6, 'barang_id' => 1, 'harga' => 8500000, 'jumlah' => 1],
            ['detail_id' => 17, 'penjualan_id' => 6, 'barang_id' => 6, 'harga' => 189000, 'jumlah' => 3],
            ['detail_id' => 18, 'penjualan_id' => 6, 'barang_id' => 11, 'harga' => 98000, 'jumlah' => 1],
            
            // Penjualan 7 (3 items)
            ['detail_id' => 19, 'penjualan_id' => 7, 'barang_id' => 3, 'harga' => 550000, 'jumlah' => 1],
            ['detail_id' => 20, 'penjualan_id' => 7, 'barang_id' => 8, 'harga' => 299000, 'jumlah' => 2],
            ['detail_id' => 21, 'penjualan_id' => 7, 'barang_id' => 13, 'harga' => 18000, 'jumlah' => 5],
            
            // Penjualan 8 (3 items)
            ['detail_id' => 22, 'penjualan_id' => 8, 'barang_id' => 2, 'harga' => 3800000, 'jumlah' => 1],
            ['detail_id' => 23, 'penjualan_id' => 8, 'barang_id' => 7, 'harga' => 225000, 'jumlah' => 2],
            ['detail_id' => 24, 'penjualan_id' => 8, 'barang_id' => 12, 'harga' => 35000, 'jumlah' => 3],
            
            // Penjualan 9 (3 items)
            ['detail_id' => 25, 'penjualan_id' => 9, 'barang_id' => 5, 'harga' => 110000, 'jumlah' => 2],
            ['detail_id' => 26, 'penjualan_id' => 9, 'barang_id' => 10, 'harga' => 75000, 'jumlah' => 3],
            ['detail_id' => 27, 'penjualan_id' => 9, 'barang_id' => 15, 'harga' => 180000, 'jumlah' => 2],
            
            // Penjualan 10 (3 items)
            ['detail_id' => 28, 'penjualan_id' => 10, 'barang_id' => 4, 'harga' => 399000, 'jumlah' => 2],
            ['detail_id' => 29, 'penjualan_id' => 10, 'barang_id' => 9, 'harga' => 250000, 'jumlah' => 1],
            ['detail_id' => 30, 'penjualan_id' => 10, 'barang_id' => 14, 'harga' => 45000, 'jumlah' => 4],
        ];
        DB::table('t_penjualan_detail')->insert($data);
    }
}
