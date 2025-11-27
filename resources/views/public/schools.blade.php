@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row mb-4">
        <div class="col-12 text-center">
            <h2 class="fw-bold">Daftar Sekolah</h2>
            <p class="text-muted">Pilih sekolah untuk melihat daftar menu makanan yang tersedia</p>
        </div>
    </div>

    <div class="row g-4">
        @forelse($schools as $school)
            <div class="col-md-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">{{ $school->name }}</h5>
                        <p class="card-text text-muted">{{ $school->address }}</p>
                        <a href="{{ route('schools.show', $school->id) }}" class="btn btn-primary">
                            Lihat Menu <i class="bi bi-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-info">
                    <i class="bi bi-info-circle me-2"></i> Belum ada data sekolah yang tersedia.
                </div>
            </div>
        @endforelse
    </div>
</div>
@endsection
