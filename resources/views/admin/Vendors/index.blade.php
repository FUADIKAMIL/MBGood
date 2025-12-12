@extends('layouts.app')

@section('content')
@php
    $assignedSchoolIds = $assignedSchoolIds ?? [];
    $availableSchools = $schools->filter(fn($school) => !in_array($school->id, $assignedSchoolIds));
@endphp
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

            @if($errors->any())
                <div class="alert alert-danger small">
                    <strong>Terjadi kesalahan:</strong>
                    <ul class="mb-0 mt-1 ps-3">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
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
                                <label class="form-label small fw-semibold">Nama SPPG / Vendor</label>
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
                                <label class="form-label small fw-semibold">Kontak (WA / Telepon)</label>
                                <input type="text" name="contact" class="form-control form-control-sm"
                                       value="{{ old('contact') }}">
                            </div>

                            <div class="col-12">
                                <label class="form-label small fw-semibold">Sekolah yang Ditangani</label>
                                <div class="input-group input-group-sm mb-2">
                                    <span class="input-group-text bg-white"><i class="bi bi-search text-muted"></i></span>
                                    <input type="text" class="form-control school-filter-input" data-target="createSchoolSelect"
                                           placeholder="Cari sekolah...">
                                </div>
                                <select id="createSchoolSelect" name="school_ids[]" class="form-select form-select-sm" multiple size="6" data-filterable="true">
                                    @forelse($availableSchools as $school)
                                        <option value="{{ $school->id }}" {{ in_array($school->id, old('school_ids', [])) ? 'selected' : '' }}>
                                            {{ $school->name }} - {{ $school->region }}
                                        </option>
                                    @empty
                                        <option value="" disabled>Tidak ada sekolah tersedia (semua sudah ditugaskan)</option>
                                    @endforelse
                                </select>
                                @if($availableSchools->isEmpty())
                                    <small class="text-muted">Kosongkan dan gunakan menu Edit untuk mengelola penugasan jika dibutuhkan.</small>
                                @else
                                    <small class="text-muted">Tahan Ctrl / Cmd untuk memilih lebih dari satu sekolah.</small>
                                @endif
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
                    <div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-3">
                        <div>
                            <h6 class="fw-semibold mb-1">
                                <i class="bi bi-people me-2 text-primary"></i>
                                Daftar Akun SPPG
                            </h6>
                            <small class="text-muted">Total: {{ $vendors->total() }} akun</small>
                            @if($search)
                                <div class="mt-1">
                                    <span class="badge text-bg-light text-muted small">Hasil pencarian: "{{ $search }}"</span>
                                </div>
                            @endif
                        </div>

                        <form class="d-flex flex-column flex-sm-row gap-2 align-items-stretch" method="GET" action="{{ route('admin.vendors.index') }}">
                            <div class="input-group input-group-sm">
                                <span class="input-group-text bg-white"><i class="bi bi-search text-muted"></i></span>
                                <input type="search" name="q" class="form-control" placeholder="Cari nama, email, kontak" value="{{ $search }}">
                            </div>
                            <div class="d-flex gap-2">
                                <button class="btn btn-primary btn-sm" type="submit">Cari</button>
                                @if($search)
                                    <a href="{{ route('admin.vendors.index') }}" class="btn btn-outline-secondary btn-sm" title="Hapus pencarian">
                                        Reset
                                    </a>
                                @endif
                            </div>
                        </form>
                    </div>

                    @if($vendors->isEmpty())
                        <p class="text-muted small mb-0">Belum ada akun SPPG terdaftar.</p>
                    @else
                        <div class="table-responsive">
                            <table class="table table-sm align-middle">
                                <thead>
                                    <tr class="small text-muted">
                                        <th style="width:40px;">#</th>
                                        <th>Nama & Email</th>
                                        <th style="width:130px;">Kontak</th>
                                        <th>Sekolah Ditangani</th>
                                        <th style="width:120px;" class="text-end">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($vendors as $vendor)
                                        <tr class="small align-top">
                                            <td>{{ $vendors->firstItem() + $loop->index }}</td>
                                            <td>
                                                <div class="fw-semibold">{{ $vendor->user->name }}</div>
                                                <div class="text-muted">{{ $vendor->user->email }}</div>
                                            </td>
                                            <td>{{ $vendor->contact ?? '-' }}</td>
                                            <td>
                                                @forelse($vendor->schools as $school)
                                                    <span class="badge text-bg-light text-secondary me-1 mb-1">{{ $school->name }}</span>
                                                @empty
                                                    <span class="text-muted">Belum ada sekolah</span>
                                                @endforelse
                                            </td>
                                            <td class="text-end">
                                                <button class="btn btn-outline-primary btn-sm"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#editVendorModal{{ $vendor->id }}">
                                                    <i class="bi bi-pencil-square me-1"></i> Edit
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="d-flex justify-content-center mt-3">
                            {{ $vendors->links() }}
                        </div>
                    @endif

                </div>
            </div>

        </div>
    </div>
</div>

@foreach($vendors as $vendor)
    <div class="modal fade" id="editVendorModal{{ $vendor->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Akun SPPG</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                @php
                    $selectedSchools = $vendor->schools->pluck('id')->toArray();
                    $selectId = 'editSchoolSelect' . $vendor->id;
                @endphp

                <form id="updateVendorForm{{ $vendor->id }}"
                      action="{{ route('admin.vendors.update', $vendor) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold">Nama SPPG / Vendor</label>
                                <input type="text" name="name" class="form-control form-control-sm"
                                       value="{{ $vendor->user->name }}" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label small fw-semibold">Email Login</label>
                                <input type="email" name="email" class="form-control form-control-sm"
                                       value="{{ $vendor->user->email }}" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label small fw-semibold">Password Baru (opsional)</label>
                                <input type="password" name="password" class="form-control form-control-sm"
                                       placeholder="Kosongkan jika tidak diganti">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label small fw-semibold">Kontak (WA / Telepon)</label>
                                <input type="text" name="contact" class="form-control form-control-sm"
                                       value="{{ $vendor->contact }}">
                            </div>

                            <div class="col-12">
                                <label class="form-label small fw-semibold">Sekolah yang Ditangani</label>
                                <div class="input-group input-group-sm mb-2">
                                    <span class="input-group-text bg-white"><i class="bi bi-search text-muted"></i></span>
                                    <input type="text" class="form-control school-filter-input" data-target="{{ $selectId }}"
                                           placeholder="Cari sekolah...">
                                </div>
                                <select id="{{ $selectId }}" name="school_ids[]" class="form-select form-select-sm" multiple size="6" data-filterable="true">
                                    @foreach($schools as $school)
                                        @php
                                            $isAssignedToOthers = in_array($school->id, $assignedSchoolIds) && !in_array($school->id, $selectedSchools);
                                        @endphp
                                        @if($isAssignedToOthers)
                                            @continue
                                        @endif
                                        <option value="{{ $school->id }}" {{ in_array($school->id, $selectedSchools) ? 'selected' : '' }}>
                                            {{ $school->name }} - {{ $school->region }}
                                        </option>
                                    @endforeach
                                </select>
                                <small class="text-muted">Gunakan Ctrl / Cmd untuk memilih lebih dari satu sekolah.</small>
                            </div>
                        </div>
                    </div>
                </form>

                <div class="modal-footer flex-column gap-2">
                    <button type="submit" class="btn btn-primary w-100"
                            form="updateVendorForm{{ $vendor->id }}">
                        Simpan Perubahan
                    </button>
                    <form action="{{ route('admin.vendors.destroy', $vendor) }}" method="POST"
                          onsubmit="return confirm('Hapus akun ini beserta data terkait?');" class="w-100">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-outline-danger w-100">
                            Hapus Akun
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endforeach
@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.school-filter-input').forEach(function (input) {
                const targetId = input.dataset.target;
                const select = document.getElementById(targetId);

                if (!select || !select.dataset.filterable) {
                    return;
                }

                const originalOptions = Array.from(select.options).map(function (option) {
                    return {
                        value: option.value,
                        text: option.text,
                        selected: option.selected,
                        disabled: option.disabled,
                    };
                });

                input.addEventListener('input', function () {
                    const term = this.value.trim().toLowerCase();
                    const currentSelections = new Set(Array.from(select.selectedOptions).map(function (opt) {
                        return opt.value;
                    }));

                    select.innerHTML = '';

                    originalOptions.forEach(function (optionData) {
                        if (term && !optionData.text.toLowerCase().includes(term)) {
                            return;
                        }

                        const option = document.createElement('option');
                        option.value = optionData.value;
                        option.textContent = optionData.text;
                        option.disabled = optionData.disabled;
                        option.selected = currentSelections.has(optionData.value) || (!term && optionData.selected);
                        select.appendChild(option);
                    });
                });
            });
        });
    </script>
@endpush
@endsection
