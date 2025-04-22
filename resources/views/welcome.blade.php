@extends('layouts.template')

@section('content')

<div class="row">
    <div class="col-lg-12">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h3 class="card-title mb-0">
                    <i class="fas fa-tachometer-alt"></i> Dashboard
                </h3>
            </div>
            <div class="card-body">
                <div class="text-center mb-4">
                    <h4 class="font-weight-bold">Halo, apakabar {{ $username }}!!! 👋</h4>
                    <p class="text-muted">
                        Selamat datang di aplikasi <strong>Manajemen Penjualan</strong>. 
                        Silakan gunakan menu di sebelah kiri untuk mulai bekerja.
                    </p>
                </div>

                <div class="row text-center">
                    <div class="col-md-4">
                        <div class="info-box bg-info">
                            <span class="info-box-icon"><i class="fas fa-box"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Total Produk</span>
                                <span class="info-box-number">{{ $totalProduk }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="info-box bg-success">
                            <span class="info-box-icon"><i class="fas fa-shopping-cart"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Penjualan Hari Ini</span>
                                <span class="info-box-number">{{ $penjualanHariIni }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="info-box bg-warning">
                            <span class="info-box-icon"><i class="fas fa-users"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Pengguna Total</span>
                                <span class="info-box-number">{{ $penggunaTotal }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row justify-content-center mt-3">
                    <div class="col-md-3 d-flex justify-content-center">
                        <div class="info-box bg-success">
                            <span class="info-box-icon"><i class="fas fa-dollar-sign"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Total Penjualan</span>
                                <span class="info-box-number">Rp {{ number_format($totalPenjualan, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- <div class="alert alert-info mt-4">
                    <i class="fas fa-lightbulb"></i> Tips: Gunakan fitur <strong>Laporan Penjualan</strong> untuk melihat rekap bulanan dengan grafik!
                </div> --}}
            </div>
        </div>
    </div>
</div>

@endsection
