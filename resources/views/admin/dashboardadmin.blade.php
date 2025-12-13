@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row align-items-center mb-4">
        <div class="col-lg-8">
            <p class="text-uppercase text-primary fw-semibold mb-1" style="letter-spacing: .2rem;">Admin</p>
            <h1 class="h3 fw-bold mb-1">Dashboard Pengelolaan MBGood</h1>
            <p class="text-muted mb-0">Ringkasan vendor, sekolah, dan status pengajuan menu.</p>
        </div>
        <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
            <span class="badge bg-light text-dark px-4 py-2 fw-semibold">
                <i class="bi bi-calendar-week me-1"></i>
                {{ now()->translatedFormat('l, d F Y') }}
            </span>
        </div>
    </div>

    <!-- Stats -->
    <div class="row g-3 mb-4">
        <div class="col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <p class="text-muted small mb-1">Total Sekolah</p>
                    <h3 class="fw-bold mb-0">{{ $stats['schools'] }}</h3>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <p class="text-muted small mb-1">Total Vendor</p>
                    <h3 class="fw-bold mb-0">{{ $stats['vendors'] }}</h3>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <p class="text-muted small mb-1">Menu Pending</p>
                    <h3 class="fw-bold text-warning mb-0">{{ $stats['pendingMenus'] }}</h3>
                    <small class="text-muted">Total menu: {{ $stats['totalMenus'] }}</small>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <p class="text-muted small mb-1">Belum Punya Vendor</p>
                    <h3 class="fw-bold text-danger mb-0">{{ $stats['unassignedSchools'] }}</h3>
                    <small class="text-muted">Segera tugaskan SPPG</small>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-xl-7">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <h5 class="card-title mb-0">Menu Terbaru</h5>
                            <small class="text-muted">6 pengajuan terakhir</small>
                        </div>
                        <a href="{{ route('admin.menus.approval') }}" class="btn btn-sm btn-outline-primary">Kelola</a>
                    </div>

                    @if($recentMenus->isEmpty())
                        <p class="text-muted mb-0">Belum ada menu yang diajukan.</p>
                    @else
                        <div class="table-responsive">
                            <table class="table align-middle">
                                <thead>
                                <tr>
                                    <th>Judul</th>
                                    <th>Vendor</th>
                                    <th>Status</th>
                                    <th>Tanggal</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($recentMenus as $menu)
                                    <tr>
                                        <td>{{ $menu->title }}</td>
                                        <td>{{ $menu->vendor?->user?->name ?? '-' }}</td>
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

        <div class="col-xl-5">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="card-title mb-0">Vendor Baru</h5>
                        <a href="{{ route('admin.vendors.index') }}" class="btn btn-sm btn-outline-secondary">Kelola</a>
                    </div>

                    @if($recentVendors->isEmpty())
                        <p class="text-muted mb-0">Belum ada vendor terdaftar.</p>
                    @else
                        <ul class="list-group list-group-flush">
                            @foreach($recentVendors as $vendor)
                                <li class="list-group-item px-0 d-flex justify-content-between">
                                    <div>
                                        <div class="fw-semibold">{{ $vendor->user?->name ?? $vendor->name }}</div>
                                        <small class="text-muted">{{ $vendor->user?->email }}</small>
                                    </div>
                                    <span class="badge bg-light text-dark">{{ $vendor->created_at?->format('d M') }}</span>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title mb-3">Jadwal Distribusi Terdekat</h5>
                    @if($upcomingDailyMenus->isEmpty())
                        <p class="text-muted mb-0">Belum ada jadwal terbaru.</p>
                    @else
                        <div class="list-group list-group-flush">
                            @foreach($upcomingDailyMenus as $schedule)
                                <div class="list-group-item px-0 d-flex justify-content-between">
                                    <div>
                                        <div class="fw-semibold">{{ $schedule->school->name }}</div>
                                        <small class="text-muted">Vendor: {{ $schedule->menu->vendor?->name ?? '-' }}</small>
                                    </div>
                                    <span class="badge bg-primary-subtle text-primary">
                                        {{ \Carbon\Carbon::parse($schedule->date)->format('d M') }}
                                    </span>
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
