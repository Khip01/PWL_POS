<?php

namespace App\Http\Controllers;

use App\Models\BarangModel;
use App\Models\PenjualanDetailModel;
use App\Models\PenjualanModel;
use App\Models\UserModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WelcomeController extends Controller
{
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Selamat Datang',
            'list' => ['Home', 'Welcome']
        ];

        $activeMenu = 'dashboard';

        // Total Produk
        $totalProduk = BarangModel::count();

        // Penjualan Hari ini 
        $penjualanHariIni = PenjualanModel::whereDate('penjualan_tanggal', now()->format('Y-m-d'))->count();
        
        // Total Pengguna
        $penggunaTotal = UserModel::count();

        // Total Penjualan (Total harga penjualan)
        $totalPenjualan = PenjualanDetailModel::sum(DB::raw('harga * jumlah'));

        return view('welcome', [
            'breadcrumb' => $breadcrumb, 
            'activeMenu' => $activeMenu, 
            'username' => auth()->user()->username, 
            'totalProduk' => $totalProduk, 
            'penjualanHariIni' => $penjualanHariIni, 
            'penggunaTotal' => $penggunaTotal,
            'totalPenjualan' => $totalPenjualan,
        ]);
    }
}
