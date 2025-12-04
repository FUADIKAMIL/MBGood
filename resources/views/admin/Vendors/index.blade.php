@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">

            <div class="mb-4">
                <p class="text-uppercase text-primary fw-semibold small mb-2">Admin</p>
                <h1 class="h4 fw-bold mb-1">Manajemen Akun SPPG</h1>
                <p class="text-muted small mb-0">
                    Buat dan kelola akun vendor/SPPG yang akan mengelola menu MBGood.
                </p>
            </div>

            @if(session('success'))
                <div class="alert alert-success small">
                    {{ session('success') }}
                </div>
            @endif

            {{-- FORM TAMBAH AKUN SPPG --}}
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body">
                    <h6 class="fw-semibold mb-3">
                        <i class="bi bi-person-plus me-2 text-primary"></i>
                        Buat Akun SPPG Baru
                    </h6>

                    <form action="{{ route('admin.vendors.store') }}" method="POST">
                        @csrf

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold">Nama PIC / SPPG</label>
                                <input type="text" name="name" class="form-control form-control-sm"
                                       value="{{ old('name') }}" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label small fw-semibold">Email Login</label>
                                <input type="email" name="email" class="form-control form-control-sm"
                                       value="{{ old('email') }}" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label small fw-semibold">Password Awal</label>
                                <input type="password" name="password" class="form-control form-control-sm" required>
                                <small class="text-muted">
                                    Password bisa diganti sendiri oleh SPPG setelah login.
                                </small>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label small fw-semibold">Nama Lembaga / Perusahaan</label>
                                <input type="text" name="company_name" class="form-control form-control-sm"
                                       value="{{ old('company_name') }}" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label small fw-semibold">Kontak (WA / Telepon)</label>
                                <input type="text" name="contact" class="form-control form-control-sm"
                                       value="{{ old('contact') }}">
                            </div>
                        </div>

                        <div class="mt-3">
                            <button class="btn btn-primary btn-sm">
                                <i class="bi bi-check-circle me-1"></i> Simpan Akun
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- TABEL DAFTAR SPPG --}}
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h6 class="fw-semibold mb-3">
                        <i class="bi bi-people me-2 text-primary"></i>
                        Daftar Akun SPPG
                    </h6>

                    @if($vendors->isEmpty())
                        <p class="text-muted small mb-0">Belum ada akun SPPG terdaftar.</p>
                    @else
                        <div class="table-responsive">
                            <table class="table table-sm align-middle">
                                <thead>
                                    <tr class="small text-muted">
                                        <th>#</th>
                                        <th>Nama</th>
                                        <th>Email</th>
                                        <th>Perusahaan</th>
                                        <th>Kontak</th>
                                        <th class="text-end">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($vendors as $vendor)
                                        <tr class="small">
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $vendor->user->name }}</td>
                                            <td>{{ $vendor->user->email }}</td>
                                            <td>{{ $vendor->company_name }}</td>
                                            <td>{{ $vendor->contact }}</td>
                                            <td class="text-end">
                                                <form action="{{ route('admin.vendors.destroy', $vendor) }}"
                                                      method="POST"
                                                      onsubmit="return confirm('Hapus akun SPPG ini?');"
                                                      class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="btn btn-outline-danger btn-sm">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
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
