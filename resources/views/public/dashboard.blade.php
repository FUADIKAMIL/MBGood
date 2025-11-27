@extends('layouts.app')

@section('content')
<!-- HERO -->
<section class="py-5" style="min-height: 60vh; display: flex; align-items: center;">
    <div class="container">
        <div class="row align-items-center g-4">
            <div class="col-lg-6 mb-4 mb-lg-0">
                <p class="text-uppercase text-primary fw-semibold small mb-2">Program Makanan Bergizi Gratis</p>
                <h1 class="display-5 fw-bold mb-3">Makanan Bergizi Gratis untuk Generasi Sehat Indonesia</h1>
                <p class="lead text-muted mb-4" style="max-width: 540px;">
                    Memberikan akses pangan bergizi berkualitas untuk mendukung pertumbuhan optimal anak-anak Indonesia.
                </p>
                <div class="d-flex flex-wrap gap-3">
                    <a href="{{ route('about') }}" class="btn btn-primary btn-lg px-4">
                        <i class="bi bi-info-circle me-2"></i>Selengkapnya
                    </a>
                </div>
            </div>
            <div class="col-lg-6 text-center text-lg-end">
                <img src="{{ asset('images/hero.jpg') }}" 
                     alt="Program MBG" 
                     class="img-fluid rounded-4 shadow-lg"
                     style="width: 100%; height: auto; object-fit: cover;">
            </div>
        </div>
    </div>
</section>

<!-- Map Section + Cari Sekolah -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm overflow-hidden">
                    <div class="card-body p-0">
                        <img src="{{ asset('images/peta.webp') }}" 
                             class="img-fluid w-100" 
                             alt="Peta Sebaran MBG"
                             style="object-fit: contain; max-height: 60vh;">
                    </div>
                    <div class="card-footer bg-white text-center py-3">
                        <a href="{{ route('cari') }}" class="btn btn-primary px-4">
                            <i class="bi bi-search me-2"></i>Cari Sekolah
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Tentang MBG -->
<section class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 text-center">
                <h2 class="section-title mb-3">Tentang MBGood</h2>
                <p class="text-muted mb-0" style="max-width: 640px; margin: 0 auto;">
                    MBGood adalah dashboard sederhana untuk melihat bagaimana program Makanan Bergizi Gratis (MBG)
                    dijalankan di sekolah. Di sini Anda bisa menelusuri sekolah peserta, melihat contoh menu yang
                    disajikan, serta membaca informasi nilai gizi dan masukan publik terkait kualitas makanan.
                </p>
            </div>
        </div>
    </div>
</section>

@endsection
