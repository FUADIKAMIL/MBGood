@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">

            <div class="mb-4">
                <p class="text-uppercase text-primary fw-semibold small mb-2">Admin</p>
                <h1 class="h4 fw-bold mb-1">Manajemen Sekolah</h1>
                <p class="text-muted small mb-0">
                    Tambah, perbarui, dan kelola daftar sekolah yang bekerja sama dengan MBGood.
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

            <div class="row g-4 align-items-start">
                <div class="col-lg-5">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <h6 class="fw-semibold mb-3">
                                <i class="bi bi-building-add me-2 text-primary"></i>
                                Tambah Sekolah Baru
                            </h6>

                            <form action="{{ route('admin.schools.store') }}" method="POST" class="row g-3">
                                @csrf

                                <div class="col-12">
                                    <label class="form-label small fw-semibold">Nama Sekolah</label>
                                    <input type="text" name="name" class="form-control form-control-sm" value="{{ old('name') }}" required>
                                </div>

                                <div class="col-12">
                                    <label class="form-label small fw-semibold">Wilayah</label>
                                    <input type="text" name="region" class="form-control form-control-sm" value="{{ old('region') }}" required>
                                </div>

                                <div class="col-12">
                                    <label class="form-label small fw-semibold">Alamat</label>
                                    <textarea name="address" rows="2" class="form-control form-control-sm" required>{{ old('address') }}</textarea>
                                </div>

                                <div class="col-12">
                                    <label class="form-label small fw-semibold">Penanggung Jawab (SPPG)</label>
                                    <select name="vendor_id" class="form-select form-select-sm">
                                        <option value="">Belum ditentukan</option>
                                        @foreach($vendors as $vendor)
                                            @php
                                                $label = $vendor->user->name ?? $vendor->company_name;
                                            @endphp
                                            <option value="{{ $vendor->id }}" {{ old('vendor_id') == $vendor->id ? 'selected' : '' }}>
                                                {{ $label }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <small class="text-muted">Opsional - dapat dipilih atau diganti nanti.</small>
                                </div>

                                <div class="col-12">
                                    <button class="btn btn-primary btn-sm w-100">
                                        <i class="bi bi-check-circle me-1"></i> Simpan Sekolah
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-lg-7">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <div class="d-flex flex-column flex-md-row gap-3 align-items-md-center mb-3">
                                <div class="flex-grow-1" style="max-width: 360px;">
                                    <label class="form-label small fw-semibold mb-1">Pencarian Sekolah</label>
                                    <div class="input-group input-group-sm">
                                        <span class="input-group-text bg-white">
                                            <i class="bi bi-search text-muted"></i>
                                        </span>
                                        <input type="search"
                                               id="schoolLiveSearch"
                                               class="form-control"
                                               placeholder="Ketik nama sekolah / wilayah / vendor..."
                                               data-search-url="{{ route('admin.schools.index') }}"
                                               value="{{ $search }}">
                                    </div>
                                </div>

                                <div class="d-flex align-items-center gap-2">
                                    <div class="form-check form-switch small mb-0">
                                        <input class="form-check-input" type="checkbox" role="switch"
                                               id="unassignedSwitch" {{ $unassignedOnly ? 'checked' : '' }}>
                                        <label class="form-check-label" for="unassignedSwitch">
                                            Belum punya vendor
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div id="schoolListContainer">
                                @include('admin.schools.partials.list', [
                                    'schools' => $schools,
                                    'vendors' => $vendors,
                                ])
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<div class="modal fade" id="editSchoolModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Sekolah</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form id="editSchoolForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Nama Sekolah</label>
                            <input type="text" id="editSchoolName" name="name" class="form-control form-control-sm" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Wilayah</label>
                            <input type="text" id="editSchoolRegion" name="region" class="form-control form-control-sm" required>
                        </div>

                        <div class="col-12">
                            <label class="form-label small fw-semibold">Alamat</label>
                            <textarea id="editSchoolAddress" name="address" rows="2" class="form-control form-control-sm" required></textarea>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Penanggung Jawab (SPPG)</label>
                            <select id="editSchoolVendor" name="vendor_id" class="form-select form-select-sm">
                                <option value="">Belum ditentukan</option>
                                @foreach($vendors as $vendorOption)
                                    @php
                                        $label = $vendorOption->user->name ?? $vendorOption->company_name;
                                    @endphp
                                    <option value="{{ $vendorOption->id }}">{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </form>

            <div class="modal-footer flex-column gap-2">
                <button class="btn btn-primary w-100" type="submit" form="editSchoolForm">Simpan Perubahan</button>
                <form id="deleteSchoolForm" method="POST" class="w-100" onsubmit="return confirm('Hapus sekolah ini? Tindakan ini tidak dapat dibatalkan.');">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-outline-danger w-100" type="submit">Hapus Sekolah</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const searchInput = document.getElementById('schoolLiveSearch');
        const listContainer = document.getElementById('schoolListContainer');
        const spinner = document.getElementById('schoolListSpinner');
        const counter = document.getElementById('schoolSearchCounter');
        const unassignedSwitch = document.getElementById('unassignedSwitch');

        if (!searchInput || !listContainer) {
            return;
        }

        let currentController = null;
        let lastQuery = searchInput.value.trim();
        const baseUrl = searchInput.dataset.searchUrl;
        const debounce = (fn, delay = 300) => {
            let timeout = null;
            return (...args) => {
                clearTimeout(timeout);
                timeout = setTimeout(() => fn(...args), delay);
            };
        };

        const buildUrl = (targetUrl) => {
            try {
                return new URL(targetUrl);
            } catch (error) {
                return new URL(targetUrl, window.location.origin);
            }
        };

        const toggleSpinner = (state) => {
            if (!spinner) {
                return;
            }
            spinner.style.display = state ? 'flex' : 'none';
        };

        const fetchSchools = (targetUrl = baseUrl) => {
            if (!targetUrl) {
                return;
            }

            if (currentController) {
                currentController.abort();
            }

            currentController = new AbortController();
            const url = buildUrl(targetUrl);

            if (lastQuery) {
                url.searchParams.set('q', lastQuery);
            } else {
                url.searchParams.delete('q');
            }

            if (unassignedSwitch && unassignedSwitch.checked) {
                url.searchParams.set('unassigned', '1');
            } else {
                url.searchParams.delete('unassigned');
            }

            toggleSpinner(true);

            fetch(url.toString(), {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                },
                signal: currentController.signal,
            })
                .then(response => response.ok ? response.json() : Promise.reject())
                .then(data => {
                    if (data.html) {
                        listContainer.innerHTML = data.html;
                    }
                    if (counter && typeof data.total !== 'undefined') {
                        counter.textContent = `Total: ${data.total} sekolah`;
                    }
                })
                .catch(error => {
                    if (error.name === 'AbortError') {
                        return;
                    }
                    console.error('Gagal memuat sekolah', error);
                })
                .finally(() => toggleSpinner(false));
        };

        const handleSearch = debounce(() => {
            lastQuery = searchInput.value.trim();
            fetchSchools();
        }, 320);

        searchInput.addEventListener('input', handleSearch);

        if (unassignedSwitch) {
            unassignedSwitch.addEventListener('change', () => {
                fetchSchools();
            });
        }

        document.addEventListener('click', function (event) {
            const link = event.target.closest('.ajax-pagination a');
            if (link) {
                event.preventDefault();
                fetchSchools(link.href);
                return;
            }

            const editButton = event.target.closest('.edit-school-btn');
            if (!editButton) {
                return;
            }
            event.preventDefault();

            const editModalEl = document.getElementById('editSchoolModal');
            const editForm = document.getElementById('editSchoolForm');
            const deleteForm = document.getElementById('deleteSchoolForm');
            const nameInput = document.getElementById('editSchoolName');
            const regionInput = document.getElementById('editSchoolRegion');
            const addressInput = document.getElementById('editSchoolAddress');
            const vendorSelect = document.getElementById('editSchoolVendor');

            if (!editModalEl || !editForm || !deleteForm) {
                return;
            }

            editForm.action = editButton.dataset.updateUrl;
            deleteForm.action = editButton.dataset.deleteUrl;

            if (nameInput) {
                nameInput.value = editButton.dataset.name || '';
            }
            if (regionInput) {
                regionInput.value = editButton.dataset.region || '';
            }
            if (addressInput) {
                addressInput.value = editButton.dataset.address || '';
            }
            if (vendorSelect) {
                vendorSelect.value = editButton.dataset.vendorId || '';
            }

            if (window.bootstrap) {
                const modalInstance = bootstrap.Modal.getOrCreateInstance(editModalEl);
                modalInstance.show();
            }
        });
    });
</script>
@endpush
