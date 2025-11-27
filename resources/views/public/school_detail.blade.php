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

            <!-- Menu  -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body">
                    <h5 class="card-title mb-2">
                        <i class="bi bi-egg-fried text-primary"></i> Menu MBG
                    </h5>
                    <p class="text-muted small mb-4">Daftar menu yang terdaftar untuk sekolah ini.</p>
                    
                    @if($menus->count() == 0)
                        <div class="text-center py-4">
                            <i class="bi bi-egg-fried text-muted" style="font-size: 2.5rem;"></i>
                            <p class="text-muted mt-2 mb-0">Belum ada menu tersedia</p>
                        </div>
                    @else
                        <div class="d-flex flex-column gap-3">
                            @foreach($menus as $menu)
                            <div class="card border-0 shadow-sm">
                                <div class="card-body d-flex flex-column flex-md-row justify-content-between align-items-start gap-3">
                                    <div>
                                        <h6 class="mb-1 fw-semibold">{{ $menu->title }}</h6>
                                        <div class="text-muted small mb-1">
                                            {{ Str::limit($menu->description, 90) }}
                                        </div>
                                    </div>
                                    <div class="text-md-end w-100 w-md-auto d-flex flex-md-column align-items-stretch align-items-md-end gap-2">
                                        @php
                                            $status = $menu->status ?? 'Tidak diketahui';
                                            $statusNormalized = Str::lower($status);
                                            $statusClass = 'bg-secondary';

                                            if ($statusNormalized === 'approved' || $statusNormalized === 'disetujui') {
                                                $statusClass = 'bg-success bg-opacity-10 text-success';
                                            } elseif ($statusNormalized === 'pending' || $statusNormalized === 'menunggu') {
                                                $statusClass = 'bg-warning text-dark';
                                            } elseif ($statusNormalized === 'rejected' || $statusNormalized === 'ditolak') {
                                                $statusClass = 'bg-danger';
                                            }
                                        @endphp
                                        <span class="badge {{ $statusClass }} align-self-md-end">
                                            {{ $status }}
                                        </span>
                                        <a href="{{ route('menu.show', $menu->id) }}" class="btn btn-primary btn-sm ms-auto ms-md-0">
                                            Detail Menu
                                        </a>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
@endsection