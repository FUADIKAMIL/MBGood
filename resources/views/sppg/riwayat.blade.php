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

                    <div class="d-flex flex-column flex-md-row justify-content-between gap-3 mb-3">
                        <div>
                            <h5 class="card-title mb-1">
                                <i class="bi bi-clock-history text-primary"></i> Riwayat Input Menu Harian
                            </h5>
                            <p class="text-muted small mb-0">Filter berdasarkan tanggal untuk melihat sekolah yang sudah dijadwalkan.</p>
                        </div>
                        <form method="GET" action="{{ route('sppg.riwayat') }}" class="d-flex gap-2 align-items-center">
                            <select name="date" class="form-select form-select-sm" onchange="this.form.submit()">
                                <option value="">Semua Tanggal</option>
                                @foreach($availableDates as $date)
                                    <option value="{{ $date }}" {{ $selectedDate == $date ? 'selected' : '' }}>
                                        {{ \Carbon\Carbon::parse($date)->format('d M Y') }}
                                    </option>
                                @endforeach
                            </select>
                            @if($selectedDate)
                                <a href="{{ route('sppg.riwayat') }}" class="btn btn-outline-secondary btn-sm">Reset</a>
                            @endif
                        </form>
                    </div>

                    @if($dailyMenus->isEmpty())
                        <p class="text-muted mb-0">Belum ada menu harian yang diinput untuk filter ini.</p>
                    @else
                        <div class="table-responsive">
                            <table class="table table-sm align-middle">
                                <thead>
                                    <tr class="small text-muted">
                                        <th>Tanggal</th>
                                        <th>Sekolah</th>
                                        <th>Menu</th>
                                        <th class="text-end">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($dailyMenus as $day)
                                        <tr>
                                            <td class="fw-semibold">{{ \Carbon\Carbon::parse($day->date)->format('d M Y') }}</td>
                                            <td>{{ $day->school->name ?? '-' }}</td>
                                            <td>{{ $day->menu->title }}</td>
                                            <td class="text-end">
                                                <a href="{{ route('sppg.daily.detail', $day->id) }}" class="btn btn-outline-secondary btn-sm">Detail</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif

                </div>
            </div>

        </div>
    </div>
</div>
@endsection
