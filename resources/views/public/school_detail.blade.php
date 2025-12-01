@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-lg-8 mx-auto">

            <!-- Header -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body text-center">
                    <div class="mb-3">
                        <div class="bg-primary bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center" 
                             style="width: 80px; height: 80px;">
                            <i class="bi bi-building text-primary" style="font-size: 2rem;"></i>
                        </div>
                    </div>
                    <h1 class="h3 fw-bold mb-1">{{ $school->name }}</h1>
                    <p class="text-muted small mb-2">Sekolah peserta program Makanan Bergizi Gratis (MBG)</p>
                    <p class="text-muted mb-3">
                        <i class="bi bi-geo-alt-fill text-primary"></i> {{ $school->address }}
                    </p>
                </div>
            </div>

            <!-- MENU HARIAN -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body">

                    <h5 class="card-title mb-2">
                        <i class="bi bi-calendar2-check text-primary"></i> Menu MBG Per Hari
                    </h5>
                    <p class="text-muted small mb-4">
                        Daftar menu harian yang telah diinputkan oleh SPPG dan disajikan kepada publik.
                    </p>

                    @if($dailyMenus->count() == 0)
                        <div class="text-center py-4">
                            <i class="bi bi-calendar-x text-muted" style="font-size: 2.5rem;"></i>
                            <p class="text-muted mt-2 mb-0">Belum ada menu harian</p>
                        </div>
                    @else

                    <div class="d-flex flex-column gap-3">
                        @foreach($dailyMenus as $day)

                        <div class="card border-0 shadow-sm">
                            <div class="card-body d-flex flex-column flex-md-row justify-content-between align-items-start gap-3">

                                <div>
                                    <h6 class="mb-1 fw-semibold">
                                        {{ $day->menu->title }}
                                    </h6>

                                    <!-- Tanggal -->
                                    <span class="badge bg-primary bg-opacity-10 text-primary mb-2">
                                        <i class="bi bi-calendar-event"></i>
                                        {{ \Carbon\Carbon::parse($day->date)->translatedFormat('l, d F Y') }}
                                    </span>

                                    <div class="text-muted small mb-1">
                                        {{ Str::limit($day->menu->description, 90) }}
                                    </div>
                                </div>

                                <div class="text-md-end w-100 w-md-auto d-flex flex-md-column
                                            align-items-stretch align-items-md-end gap-2">

                                    <a href="{{ route('menu.show', $day->menu->id) }}" 
                                       class="btn btn-primary btn-sm ms-auto ms-md-0">
                                        Detail Menu
                                    </a>

                                    <a href="{{ route('daily.show', $day->id) }}" 
                                       class="btn btn-outline-secondary btn-sm ms-auto ms-md-0">
                                        Komentar Hari Ini
                                    </a>

                                </div>
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
