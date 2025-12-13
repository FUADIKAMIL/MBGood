@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-11">

            <div class="mb-4">
                <p class="text-uppercase text-primary fw-semibold small mb-1">Admin</p>
                <h1 class="h4 fw-bold mb-1">Approval Menu</h1>
                <p class="text-muted small mb-0">
                    Tinjau menu yang diajukan SPPG, lihat detailnya, lalu setujui atau tolak dengan catatan.
                </p>
            </div>

            @if(session('success'))
                <div class="alert alert-success small">{{ session('success') }}</div>
            @endif
            @if(session('info'))
                <div class="alert alert-info small">{{ session('info') }}</div>
            @endif
            @if($errors->any())
                <div class="alert alert-danger small">
                    <ul class="mb-0 ps-3">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body">
                    <div class="d-flex flex-wrap gap-2">
                        @php
                            $tabs = [
                                'all' => 'Semua',
                                'pending' => 'Menunggu',
                                'approved' => 'Disetujui',
                                'rejected' => 'Ditolak',
                            ];
                        @endphp
                        @foreach($tabs as $key => $label)
                            @php
                                $isActive = $status === $key;
                            @endphp
                            <a href="{{ route('admin.menus.approval', ['status' => $key]) }}"
                               class="btn btn-sm {{ $isActive ? 'btn-primary' : 'btn-outline-primary' }}">
                                {{ $label }}
                                <span class="badge bg-white text-primary ms-1">{{ $statusCounts[$key] ?? 0 }}</span>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body">
                    <form method="GET" action="{{ route('admin.menus.approval') }}" id="vendorFilterForm">
                        <input type="hidden" name="status" value="{{ $status }}">
                        <input type="hidden" name="vendor" id="vendorFilterValue" value="{{ request('vendor') }}">

                        <div class="d-flex flex-column flex-md-row gap-3 align-items-md-center mb-1">
                            <div class="flex-grow-1" style="max-width: 360px;">
                                <label class="form-label small fw-semibold mb-1">Pencarian SPPG</label>
                                <div class="input-group input-group-sm position-relative">
                                    <span class="input-group-text bg-white">
                                        <i class="bi bi-search text-muted"></i>
                                    </span>
                                    <input type="text"
                                           id="vendorSearchInput"
                                           class="form-control"
                                           placeholder="Ketik nama SPPG..."
                                           value="{{ $selectedVendorLabel }}"
                                           autocomplete="off"
                                           data-options='@json($vendorOptions)'>
                                    <button type="button" class="btn btn-outline-secondary" id="vendorFilterReset">
                                        <i class="bi bi-x-lg"></i>
                                    </button>
                                    <div id="vendorSearchDropdown"
                                         class="list-group position-absolute top-100 start-0 w-100 shadow-sm d-none"
                                         style="z-index: 1051; max-height: 220px; overflow-y: auto;">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <p class="text-muted small mb-0">Pilih nama SPPG untuk menampilkan menu dan status yang diajukan.</p>
                    </form>
                </div>
            </div>

            @if($selectedVendor)
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white border-0">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="text-uppercase text-muted fw-semibold small mb-0">Pengajuan Aktif</p>
                                <h5 class="mb-0">{{ $selectedVendor->user->name ?? $selectedVendor->company_name }}</h5>
                            </div>
                            <span class="badge bg-warning text-dark">{{ $proposedMenus->count() }} Pending</span>
                        </div>
                    </div>
                    <div class="card-body">
                        @if($proposedMenus->isEmpty())
                            <p class="text-muted small mb-0">SPPG ini belum memiliki menu yang menunggu persetujuan.</p>
                        @else
                            <div class="table-responsive">
                                <table class="table table-sm align-middle">
                                    <thead>
                                        <tr class="small text-muted">
                                            <th>Judul</th>
                                            <th>Deskripsi</th>
                                            <th>Status</th>
                                            <th class="text-end">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($proposedMenus as $menu)
                                            <tr>
                                                <td class="fw-semibold">{{ $menu->title }}</td>
                                                <td class="small text-muted">{{ Str::limit($menu->description, 80) }}</td>
                                                <td>
                                                    <span class="badge bg-warning text-dark">{{ ucfirst($menu->status) }}</span>
                                                </td>
                                                <td class="text-end">
                                                    <button class="btn btn-outline-secondary btn-sm detail-menu-btn"
                                                            data-menu='@json($menu)'
                                                            data-approve-url="{{ route('admin.menus.approve', $menu) }}"
                                                            data-reject-url="{{ route('admin.menus.reject', $menu) }}">
                                                        Detail
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            @if($menus->isEmpty())
                <div class="alert alert-light border">Belum ada menu pada filter ini.</div>
            @else
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="list-group list-group-flush">
                            @foreach($menus as $menu)
                                <div class="list-group-item px-0 py-3">
                                    <div class="d-flex flex-column flex-lg-row justify-content-between gap-3">
                                        <div>
                                            <div class="d-flex align-items-center gap-2 mb-1">
                                                <span class="badge {{ $menu->status === 'approved' ? 'bg-success' : ($menu->status === 'rejected' ? 'bg-danger' : 'bg-warning text-dark') }}">{{ ucfirst($menu->status) }}</span>
                                                <h5 class="h6 mb-0">{{ $menu->title }}</h5>
                                            </div>
                                            <p class="text-muted small mb-1">Oleh: {{ $menu->vendor->user->name ?? $menu->vendor->company_name }}</p>
                                            <p class="small mb-0">{{ Str::limit($menu->description, 120) }}</p>
                                            @if($menu->status === 'rejected' && $menu->rejection_reason)
                                                <div class="mt-2">
                                                    <small class="text-muted">Alasan penolakan:</small>
                                                    <div class="alert alert-light border small mb-0">{{ $menu->rejection_reason }}</div>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="text-lg-end d-flex flex-row flex-lg-column gap-2">
                                            <button class="btn btn-outline-secondary btn-sm px-3 detail-menu-btn"
                                                    data-menu='@json($menu)'
                                                    data-approve-url="{{ route('admin.menus.approve', $menu) }}"
                                                    data-reject-url="{{ route('admin.menus.reject', $menu) }}">
                                                Detail
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="d-flex justify-content-center mt-3">
                            {{ $menus->links() }}
                        </div>
                    </div>
                </div>
            @endif

        </div>
    </div>
</div>

<div class="modal fade" id="menuDetailModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detail Menu</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <h5 id="detailMenuTitle" class="h5 mb-1"></h5>
                    <p class="text-muted small mb-0" id="detailMenuVendor"></p>
                </div>
                <p id="detailMenuDescription"></p>

                <div class="mt-3" id="detailMenuItems"></div>
                <div class="mt-3" id="detailMenuNutrition"></div>

                <div id="detailStatusNote" class="alert alert-light border small mt-4 d-none"></div>

                <div id="detailActionPanel" class="mt-4 border-top pt-3 d-none">
                    <div class="row g-3 align-items-end">
                        <div class="col-md-4 col-lg-3">
                            <form id="detailApproveForm" method="POST">
                                @csrf
                                <button class="btn btn-success w-100" type="submit">Terima Menu</button>
                            </form>
                        </div>
                        <div class="col-md-8 col-lg-9">
                            <form id="detailRejectForm" method="POST">
                                @csrf
                                <label class="form-label small fw-semibold">Alasan Penolakan</label>
                                <textarea id="detailRejectReason" name="rejection_reason" rows="2" class="form-control mb-2" placeholder="Tuliskan catatan penolakan..."></textarea>
                                <button class="btn btn-danger w-100" type="submit">Tolak Menu</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const detailModal = document.getElementById('menuDetailModal');
        const actionPanel = document.getElementById('detailActionPanel');
        const statusNote = document.getElementById('detailStatusNote');
        const approveForm = document.getElementById('detailApproveForm');
        const rejectForm = document.getElementById('detailRejectForm');
        const rejectReasonInput = document.getElementById('detailRejectReason');

        document.querySelectorAll('.detail-menu-btn').forEach(button => {
            button.addEventListener('click', () => {
                const data = JSON.parse(button.dataset.menu);
                document.getElementById('detailMenuTitle').textContent = data.title;
                document.getElementById('detailMenuVendor').textContent = data.vendor?.user?.name ?? data.vendor?.company_name ?? '-';
                document.getElementById('detailMenuDescription').textContent = data.description;

                const itemsContainer = document.getElementById('detailMenuItems');
                itemsContainer.innerHTML = '';
                if (data.items?.length) {
                    let rows = '';
                    data.items.forEach(item => {
                        rows += `
                            <tr>
                                <td>${item.name}</td>
                                <td>${item.portion ?? '-'}</td>
                                <td>${item.category ?? '-'}</td>
                            </tr>
                        `;
                    });
                    itemsContainer.innerHTML = `
                        <strong>Rincian Menu:</strong>
                        <div class="table-responsive mt-2">
                            <table class="table table-sm">
                                <thead>
                                    <tr class="small text-muted">
                                        <th>Nama Item</th>
                                        <th>Porsi</th>
                                        <th>Kategori</th>
                                    </tr>
                                </thead>
                                <tbody>${rows}</tbody>
                            </table>
                        </div>
                    `;
                } else {
                    itemsContainer.innerHTML = '<em class="text-muted small">Belum ada item detail.</em>';
                }

                const nutritionContainer = document.getElementById('detailMenuNutrition');
                nutritionContainer.innerHTML = '';
                if (data.nutrition) {
                    const vitamins = Array.isArray(data.nutrition.vitamins)
                        ? data.nutrition.vitamins.join(', ')
                        : (data.nutrition.vitamins ?? '-');
                    nutritionContainer.innerHTML = `
                        <strong>Informasi Gizi:</strong>
                        <ul class="mb-0 small">
                            <li>Kalori: ${data.nutrition.calories ?? '-'} kcal</li>
                            <li>Protein: ${data.nutrition.protein ?? '-'} g</li>
                            <li>Lemak: ${data.nutrition.fat ?? '-'} g</li>
                            <li>Karbohidrat: ${data.nutrition.carbs ?? '-'} g</li>
                            <li>Vitamin: ${vitamins || '-'}</li>
                        </ul>
                    `;
                } else {
                    nutritionContainer.innerHTML = '<em class="text-muted small">Belum ada informasi gizi.</em>';
                }

                if (data.status === 'pending') {
                    actionPanel.classList.remove('d-none');
                    statusNote.classList.add('d-none');
                    approveForm.action = button.dataset.approveUrl;
                    rejectForm.action = button.dataset.rejectUrl;
                    rejectReasonInput.value = '';
                } else {
                    actionPanel.classList.add('d-none');
                    statusNote.classList.remove('d-none');
                    let message = `Status saat ini: ${data.status}`;
                    if (data.status === 'rejected' && data.rejection_reason) {
                        message += ` — Catatan: ${data.rejection_reason}`;
                    }
                    statusNote.textContent = message;
                }

                bootstrap.Modal.getOrCreateInstance(detailModal).show();
            });
        });

        if (approveForm) {
            approveForm.addEventListener('submit', (event) => {
                if (!confirm('Setujui menu ini?')) {
                    event.preventDefault();
                }
            });
        }

        if (rejectForm) {
            rejectForm.addEventListener('submit', (event) => {
                if (!rejectReasonInput.value.trim()) {
                    event.preventDefault();
                    rejectReasonInput.focus();
                    alert('Mohon isi alasan penolakan.');
                }
            });
        }

        const vendorFilterForm = document.getElementById('vendorFilterForm');
        const vendorFilterValue = document.getElementById('vendorFilterValue');
        const vendorSearchInput = document.getElementById('vendorSearchInput');
        const vendorResetButton = document.getElementById('vendorFilterReset');
        const vendorDropdown = document.getElementById('vendorSearchDropdown');

        if (vendorFilterForm && vendorFilterValue && vendorSearchInput && vendorDropdown) {
            let vendorOptions = [];
            try {
                vendorOptions = JSON.parse(vendorSearchInput.dataset.options || '[]');
            } catch (error) {
                vendorOptions = [];
            }

            const optionMap = vendorOptions.reduce((map, option) => {
                map[option.label.trim().toLowerCase()] = option.id;
                return map;
            }, {});

            const renderDropdown = (query = '') => {
                const normalized = query.trim().toLowerCase();
                const matches = vendorOptions.filter(option =>
                    !normalized || option.label.toLowerCase().includes(normalized)
                );

                const items = [
                    {
                        id: '',
                        label: 'Semua SPPG',
                    },
                    ...matches,
                ];

                if (!items.length) {
                    vendorDropdown.classList.add('d-none');
                    vendorDropdown.innerHTML = '';
                    return;
                }

                vendorDropdown.innerHTML = items.map(item => `
                    <button type="button" class="list-group-item list-group-item-action" data-id="${item.id}" data-label="${item.label}">
                        ${item.label}
                    </button>
                `).join('');

                vendorDropdown.classList.remove('d-none');
            };

            const submitFilterForm = () => {
                if (typeof vendorFilterForm.requestSubmit === 'function') {
                    vendorFilterForm.requestSubmit();
                } else {
                    vendorFilterForm.submit();
                }
            };

            const handleVendorInput = (value) => {
                const normalized = value.trim().toLowerCase();

                if (!value || normalized === 'semua sppg') {
                    vendorFilterValue.value = '';
                    submitFilterForm();
                    return;
                }

                if (Object.prototype.hasOwnProperty.call(optionMap, normalized)) {
                    vendorFilterValue.value = optionMap[normalized];
                    submitFilterForm();
                }
            };

            vendorSearchInput.addEventListener('focus', (event) => {
                renderDropdown(event.target.value || '');
            });

            vendorSearchInput.addEventListener('input', (event) => {
                const value = event.target.value || '';
                renderDropdown(value);
                handleVendorInput(value);
            });

            vendorSearchInput.addEventListener('keydown', (event) => {
                if (event.key === 'Enter') {
                    event.preventDefault();
                    const firstOption = vendorDropdown.querySelector('.list-group-item');
                    if (firstOption) {
                        firstOption.click();
                    }
                }
            });

            vendorDropdown.addEventListener('click', (event) => {
                const button = event.target.closest('.list-group-item');
                if (!button) {
                    return;
                }

                const selectedId = button.dataset.id || '';
                const selectedLabel = button.dataset.label || '';

                vendorSearchInput.value = selectedLabel;
                vendorFilterValue.value = selectedId;
                vendorDropdown.classList.add('d-none');
                submitFilterForm();
            });

            vendorSearchInput.addEventListener('blur', () => {
                setTimeout(() => vendorDropdown.classList.add('d-none'), 120);
            });

            vendorResetButton?.addEventListener('click', () => {
                vendorSearchInput.value = '';
                vendorFilterValue.value = '';
                vendorDropdown.classList.add('d-none');
                submitFilterForm();
            });
        }
    });
</script>
@endpush
