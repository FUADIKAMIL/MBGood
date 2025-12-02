@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-lg-10 mx-auto">

            <!-- HEADER -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body text-center">
                    <div class="mb-3">
                        <div class="bg-success bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center"
                             style="width: 80px; height: 80px;">
                            <i class="bi bi-person-workspace text-success" style="font-size: 2rem;"></i>
                        </div>
                    </div>

                    <h2 class="fw-bold mb-1">Dashboard SPPG</h2>
                    <p class="text-muted small">Kelola menu MBG untuk sekolah-sekolah.</p>
                </div>
            </div>

            <!-- LIST MENU HARIAN YANG SUDAH DIBUAT -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body">

                    <h5 class="card-title mb-2">
                        <i class="bi bi-clock-history text-primary"></i> Riwayat Input Menu Harian
                    </h5>

                    <p class="text-muted small mb-3">
                        Riwayat menu yang telah diinputkan oleh SPPG.
                    </p>

                    @if($dailyMenus->count() == 0)
                        <p class="text-muted">Belum ada menu harian yang diinput.</p>
                    @else
                        <div class="d-flex flex-column gap-3">
                            @foreach($dailyMenus as $day)
                            <div class="card border-0 shadow-sm">
                                <div class="card-body d-flex justify-content-between">
                                    <div>
                                        <strong>{{ $day->menu->title }}</strong><br>
                                        <span class="text-muted small">
                                            {{ \Carbon\Carbon::parse($day->date)->format('d F Y') }}
                                        </span>
                                    </div>
                                    <a href="{{ route('sppg.daily.detail', $day->id) }}" class="btn btn-outline-secondary btn-sm">
                                        Detail
                                    </a>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @endif

                </div>
            </div>

        </div>
    </div>
</div>
@endsection
