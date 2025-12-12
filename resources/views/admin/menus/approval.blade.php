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
                    <div class="d-flex flex-column flex-lg-row gap-3">
                        <form id="detailApproveForm" method="POST">
                            @csrf
                            <button class="btn btn-success w-100" type="submit">Terima Menu</button>
                        </form>

                        <form id="detailRejectForm" method="POST" class="flex-grow-1">
                            @csrf
                            <div class="mb-2">
                                <label class="form-label small fw-semibold">Alasan Penolakan</label>
                                <textarea id="detailRejectReason" name="rejection_reason" rows="2" class="form-control" placeholder="Tuliskan catatan penolakan..."></textarea>
                            </div>
                            <button class="btn btn-danger w-100" type="submit">Tolak Menu</button>
                        </form>
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
                                <td>${item.description ?? '-'}</td>
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
                                        <th>Catatan</th>
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
    });
</script>
@endpush
