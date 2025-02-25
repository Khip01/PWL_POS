<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SupplierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['supplier_id' => 1, 'supplier_kode' => 'SUP-001', 'supplier_nama' => 'PT Maju Bersama', 'supplier_alamat' => 'Jl. Raya Utama No. 123, Jakarta Pusat'],
            ['supplier_id' => 2, 'supplier_kode' => 'SUP-002', 'supplier_nama' => 'CV Sejahtera Abadi', 'supplier_alamat' => 'Jl. Pahlawan No. 45, Bandung'],
            ['supplier_id' => 3, 'supplier_kode' => 'SUP-003', 'supplier_nama' => 'UD Makmur Jaya', 'supplier_alamat' => 'Jl. Diponegoro No. 78, Surabaya'],
        ];
        DB::table('m_supplier')->insert($data);
    }
}
