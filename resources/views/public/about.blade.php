@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center mb-4">
        <div class="col-lg-8 text-center">
            <p class="text-uppercase text-primary fw-semibold small mb-2">Program Makanan Bergizi Gratis</p>
            <h1 class="display-5 fw-bold mb-3">Tentang Program MBG</h1>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm mb-5">
                <div class="card-body p-4 p-lg-5">
                    <p class="lead mb-4">
                        Makanan Bergizi Gratis (MBG) adalah program yang bertujuan untuk memastikan setiap anak Indonesia 
                        mendapatkan akses terhadap makanan bergizi yang memadai untuk mendukung pertumbuhan dan perkembangan optimal.
                    </p>
                    
                    <h4 class="mt-4 mb-3">Visi Program</h4>
                    <p class="mb-3">
                        Terwujudnya generasi Indonesia yang sehat, cerdas, dan berprestasi melalui pemenuhan gizi yang optimal 
                        sejak dini.
                    </p>
                    
                    <h4 class="mt-4 mb-3">Misi Program MBG</h4>
                    <ul class="list-unstyled">
                        <li class="mb-2"><i class="bi bi-check-circle-fill text-primary me-2"></i> Menyediakan akses makanan bergizi untuk anak sekolah</li>
                        <li class="mb-2"><i class="bi bi-check-circle-fill text-primary me-2"></i> Meningkatkan kesadaran akan pentingnya gizi seimbang</li>
                        <li class="mb-2"><i class="bi bi-check-circle-fill text-primary me-2"></i> Mendukung program pemerintah dalam peningkatan kualitas SDM</li>
                        <li class="mb-2"><i class="bi bi-check-circle-fill text-primary me-2"></i> Membangun kemitraan dengan berbagai pihak terkait</li>
                    </ul>
                    
                    <div class="text-center mt-5">
                        <a href="{{ route('home') }}" class="btn btn-primary px-4">
                            <i class="bi bi-arrow-left me-2"></i>Kembali ke Beranda
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
