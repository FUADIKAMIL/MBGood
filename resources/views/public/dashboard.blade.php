@extends('layouts.app')

@section('content')

<!-- HERO -->
<section class="text-center text-light d-flex align-items-center"
         style="height: 100vh; background: url('{{ asset("images/hero.jpg") }}') center/cover no-repeat;">
    <div class="container">
        <h1 class="display-3 fw-bold">MBG</h1>
        <p class="lead mt-3">
            Program Makanan Bergizi Gratis untuk Masyarakat Indonesia.
        </p>
        <a href="/menu" class="btn btn-primary btn-lg mt-3">
            Learn More
        </a>
    </div>
</section>

<!-- SECTION: Sebaran MBG -->
<section class="py-5 bg-light">
    <div class="container">

        <div class="text-center mb-4">
            <h2 class="fw-bold">MBG Telah Tersebar di Seluruh Indonesia</h2>
            <p class="text-muted">
                Program Makan Bergizi Gratis (MBG) mulai dilaksanakan secara bertahap di berbagai wilayah Indonesia,
                dengan prioritas pada anak sekolah, ibu hamil, dan masyarakat rentan. Pemerintah menargetkan program ini
                menjangkau seluruh provinsi agar distribusi makanan bergizi menjadi lebih merata dan mudah diakses.
            </p>
        </div>

        <div class="text-center">
            <img src="{{ asset('images/peta.webp') }}"
                 class="img-fluid rounded shadow"
                 style="max-width: 700px;"
                 alt="Peta Indonesia">
        </div>

        <p class="text-center text-muted mt-3">
            <small>Sumber: Kementerian Koordinator Bidang Perekonomian RI, 2024</small>
        </p>

    </div>
</section>

@endsection
