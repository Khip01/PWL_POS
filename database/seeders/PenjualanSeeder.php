<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PenjualanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tanggal = Carbon::now()->format('Y-m-d H:i:s');
        
        $data = [
            ['penjualan_id' => 1, 'user_id' => 1, 'pembeli' => 'Customer 1', 'penjualan_kode' => 'PJ-0001', 'penjualan_tanggal' => $tanggal],
            ['penjualan_id' => 2, 'user_id' => 1, 'pembeli' => 'Customer 2', 'penjualan_kode' => 'PJ-0002', 'penjualan_tanggal' => $tanggal],
            ['penjualan_id' => 3, 'user_id' => 1, 'pembeli' => 'Customer 3', 'penjualan_kode' => 'PJ-0003', 'penjualan_tanggal' => $tanggal],
            ['penjualan_id' => 4, 'user_id' => 1, 'pembeli' => 'Customer 4', 'penjualan_kode' => 'PJ-0004', 'penjualan_tanggal' => $tanggal],
            ['penjualan_id' => 5, 'user_id' => 1, 'pembeli' => 'Customer 5', 'penjualan_kode' => 'PJ-0005', 'penjualan_tanggal' => $tanggal],
            ['penjualan_id' => 6, 'user_id' => 1, 'pembeli' => 'Customer 6', 'penjualan_kode' => 'PJ-0006', 'penjualan_tanggal' => $tanggal],
            ['penjualan_id' => 7, 'user_id' => 1, 'pembeli' => 'Customer 7', 'penjualan_kode' => 'PJ-0007', 'penjualan_tanggal' => $tanggal],
            ['penjualan_id' => 8, 'user_id' => 1, 'pembeli' => 'Customer 8', 'penjualan_kode' => 'PJ-0008', 'penjualan_tanggal' => $tanggal],
            ['penjualan_id' => 9, 'user_id' => 1, 'pembeli' => 'Customer 9', 'penjualan_kode' => 'PJ-0009', 'penjualan_tanggal' => $tanggal],
            ['penjualan_id' => 10, 'user_id' => 1, 'pembeli' => 'Customer 10', 'penjualan_kode' => 'PJ-0010', 'penjualan_tanggal' => $tanggal],
        ];
        DB::table('t_penjualan')->insert($data);
    }
}
