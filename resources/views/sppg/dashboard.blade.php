@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row mb-4 align-items-center">
        <div class="col-md-8">
            <p class="text-uppercase text-primary fw-semibold mb-1" style="letter-spacing: .2rem;">SPPG Dashboard</p>
            <h2 class="fw-bold mb-2">Hai, {{ $vendor->name ?? auth()->user()->name }}</h2>
            <p class="text-muted mb-0">Pantau status pengajuan menu, jadwal sekolah, dan progres harian Anda di sini.</p>
        </div>
        <div class="col-md-4 text-md-end mt-3 mt-md-0">
            <div class="badge bg-light text-dark px-4 py-2 fw-semibold">
                <i class="bi bi-calendar-week me-1"></i>
                {{ now()->translatedFormat('l, d F Y') }}
            </div>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-sm-6 col-lg-3">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <p class="text-muted small mb-1">Sekolah Ditugaskan</p>
                    <h3 class="fw-bold mb-0">{{ $stats['assignedSchools'] }}</h3>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-3">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <p class="text-muted small mb-1">Menu Disetujui</p>
                    <h3 class="fw-bold mb-0 text-success">{{ $stats['approvedMenus'] }}</h3>
                    <small class="text-muted">dari {{ $stats['totalMenus'] }} menu</small>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-3">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <p class="text-muted small mb-1">Pengajuan Pending</p>
                    <h3 class="fw-bold mb-0 text-warning">{{ $stats['pendingMenus'] }}</h3>
                    <small class="text-muted">Ditolak: {{ $stats['rejectedMenus'] }}</small>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-3">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <p class="text-muted small mb-1">Total Jadwal Harian</p>
                    <h3 class="fw-bold mb-0">{{ $stats['dailyMenus'] }}</h3>
                    <small class="text-muted">Sejak awal bergabung</small>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <h5 class="card-title mb-0">Menu Terbaru</h5>
                            <small class="text-muted">5 pengajuan terakhir</small>
                        </div>
                        <a href="{{ route('ajukan.view') }}" class="btn btn-sm btn-outline-primary">Ajukan Menu</a>
                    </div>

                    @if($recentMenus->isEmpty())
                        <p class="text-muted mb-0">Belum ada menu yang diajukan.</p>
                    @else
                        <div class="table-responsive">
                            <table class="table align-middle">
                                <thead>
                                    <tr>
                                        <th>Judul</th>
                                        <th>Status</th>
                                        <th>Diajukan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentMenus as $menu)
                                        <tr>
                                            <td>{{ $menu->title }}</td>
                                            <td>
                                                <span class="badge bg-{{ $menu->status === 'approved' ? 'success' : ($menu->status === 'rejected' ? 'danger' : 'warning text-dark') }}">
                                                    {{ ucfirst($menu->status) }}
                                                </span>
                                            </td>
                                            <td>{{ $menu->created_at?->format('d M Y') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-5">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="card-title mb-0">Jadwal Minggu Ini</h5>
                        <a href="{{ route('sppg.riwayat') }}" class="btn btn-sm btn-outline-secondary">Lihat Semua</a>
                    </div>

                    @if($upcomingSchedules->isEmpty())
                        <p class="text-muted mb-0">Belum ada jadwal terdekat.</p>
                    @else
                        <ul class="list-group list-group-flush">
                            @foreach($upcomingSchedules as $schedule)
                                <li class="list-group-item px-0 d-flex justify-content-between"> 
                                    <div>
                                        <div class="fw-semibold">{{ $schedule->school->name }}</div>
                                        <small class="text-muted">Menu: {{ $schedule->menu->title }}</small>
                                    </div>
                                    <span class="badge bg-primary-subtle text-primary">
                                        {{ \Carbon\Carbon::parse($schedule->date)->format('d M') }}
                                    </span>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title mb-3">Sekolah yang Anda Kelola</h5>
                    @if($vendor->schools->isEmpty())
                        <p class="text-muted mb-0">Belum ada sekolah yang ditugaskan.</p>
                    @else
                        <div class="d-flex flex-wrap gap-2">
                            @foreach($vendor->schools as $school)
                                <span class="badge bg-light text-dark border">{{ $school->name }}</span>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
