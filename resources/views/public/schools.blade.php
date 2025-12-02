@extends('layouts.app')

@section('content')
<div class="container py-5">
    {{-- HEADER --}}
    <div class="row mb-4">
        <div class="col-12 text-center">
            <h2 class="fw-bold">Daftar Sekolah</h2>
            <p class="text-muted mb-0">
                Pilih sekolah untuk melihat daftar menu makanan yang tersedia
            </p>
        </div>
    </div>

    {{-- FORM CARI --}}
    <div class="row justify-content-center mb-4">
        <div class="col-lg-8">
            <form id="form-search-sekolah" method="GET" action="{{ route('cari') }}">
                <div class="input-group input-group-lg">
                    <input
                        type="text"
                        id="search-sekolah"
                        name="q"
                        class="form-control"
                        placeholder="Cari sekolah berdasarkan nama atau alamat..."
                        value="{{ $search ?? '' }}"
                        autocomplete="off"
                    >
                    <button class="btn btn-primary" type="submit">Cari</button>
                </div>

                @if(!empty($search))
                    <p class="small text-muted mt-2 mb-0">
                        Hasil untuk: <strong>{{ $search }}</strong> —
                        {{ $schools->total() }} sekolah ditemukan
                    </p>
                @endif
            </form>
        </div>
    </div>

    {{-- KARTU SEKOLAH --}}
    <div class="row g-4">
        @forelse($schools as $school)
            <div class="col-md-4 col-lg-3">
                <div class="card h-100 shadow-sm">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title mb-1">{{ $school->name }}</h5>
                        <p class="card-text text-muted small mb-3">{{ $school->address }}</p>

                        <div class="mt-auto">
                            <a href="{{ route('schools.show', $school->id) }}" class="btn btn-primary w-100">
                                Lihat Menu <i class="bi bi-arrow-right ms-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-info">
                    <i class="bi bi-info-circle me-2"></i>
                    Belum ada data sekolah yang tersedia.
                </div>
            </div>
        @endforelse
    </div>

    {{-- PAGINATION --}}
    @if ($schools->hasPages())
        <div class="d-flex justify-content-center mt-4">
            {{ $schools->links() }}
        </div>
    @endif
</div>

{{-- LIVE SEARCH: auto submit setelah berhenti ngetik --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
    const input = document.getElementById('search-sekolah');
    const form  = document.getElementById('form-search-sekolah');
    let timeout = null;

    if (input && form) {
        input.addEventListener('keyup', function () {
            clearTimeout(timeout);

            timeout = setTimeout(function () {
                form.submit(); // kirim GET ?q=
            }, 400); // 0.4 detik setelah user stop ngetik
        });
    }
});
</script>
@endsection
