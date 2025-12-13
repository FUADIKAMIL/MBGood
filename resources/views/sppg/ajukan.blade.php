@extends('layouts.app')

@section('content')
@php
    use Illuminate\Support\Str;
@endphp

<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">

        <h5 class="card-title mb-3">
            <i class="bi bi-send-plus text-primary"></i> Ajukan Menu ke Admin
        </h5>

        <form action="{{ route('ajukan') }}" method="POST">
            @csrf

            <!-- JUDUL -->
            <div class="mb-3">
                <label class="form-label fw-semibold">Judul Menu</label>
                <input type="text" name="title" class="form-control" required>
            </div>

            <!-- DESKRIPSI -->
            <div class="mb-3">
                <label class="form-label fw-semibold">Deskripsi Menu</label>
                <textarea name="description" class="form-control" required></textarea>
            </div>

            <hr>

            <!-- ITEM MENU -->
            <h6 class="fw-bold">Item Item Menu</h6>
            <div id="menu-items">

                <div class="menu-item border rounded p-3 mb-3">
                    <div class="row g-2">

                        <div class="col-md-4">
                            <label class="form-label">Nama Item</label>
                            <input type="text" name="items[0][name]" class="form-control" required>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">Porsi</label>
                            <input type="text" name="items[0][portion]" class="form-control" required>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">Kategori</label>
                            <select name="items[0][category]" class="form-control" required>
                                <option value="carbs">Karbohidrat</option>
                                <option value="protein">Protein</option>
                                <option value="vegetable">Sayur</option>
                                <option value="fruit">Buah</option>
                                <option value="drink">Minuman</option>
                                <option value="other">Lainnya</option>
                            </select>
                        </div>

                    </div>
                </div>

            </div>

            <button type="button" id="add-item" class="btn btn-outline-primary btn-sm mb-3">
                Tambah Item
            </button>

            <hr>

            <!-- NUTRISI -->
            <h6 class="fw-bold">Nilai Gizi</h6>

            <div class="row">
                <div class="col-md-3 mb-3">
                    <label class="form-label">Kalori</label>
                    <input type="number" name="calories" class="form-control" required>
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label">Protein (g)</label>
                    <input type="number" name="protein" class="form-control" required>
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label">Lemak (g)</label>
                    <input type="number" name="fat" class="form-control" required>
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label">Karbohidrat (g)</label>
                    <input type="number" name="carbs" class="form-control" required>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Vitamin (pisahkan dengan koma)</label>
                <input type="text" name="vitamins" class="form-control" placeholder="A, B, C">
            </div>

            <button class="btn btn-primary">
                <i class="bi bi-send"></i> Ajukan ke Admin
            </button>

        </form>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body">
        <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 mb-3">
            <div>
                <p class="text-uppercase text-muted fw-semibold small mb-0">Riwayat Pengajuan</p>
                <h5 class="mb-0">Menu yang Diajukan ke Admin</h5>
            </div>
            <span class="badge bg-light text-dark">Total: {{ $menuSubmissions->count() }}</span>
        </div>

        @if($menuSubmissions->isEmpty())
            <div class="alert alert-light border mb-0">Belum ada menu yang diajukan. Ajukan menu pertama Anda melalui formulir di atas.</div>
        @else
            <div class="table-responsive">
                <table class="table table-sm align-middle">
                    <thead>
                        <tr class="small text-muted">
                            <th>#</th>
                            <th>Judul</th>
                            <th>Status</th>
                            <th>Diajukan</th>
                            <th>Catatan Admin</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($menuSubmissions as $index => $menu)
                            @php
                                $badgeClass = match($menu->status) {
                                    'approved' => 'bg-success',
                                    'rejected' => 'bg-danger',
                                    default => 'bg-warning text-dark'
                                };
                            @endphp
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td class="fw-semibold">{{ $menu->title }}</td>
                                <td><span class="badge {{ $badgeClass }}">{{ ucfirst($menu->status) }}</span></td>
                                <td class="small text-muted">{{ optional($menu->created_at)->format('d M Y') }}</td>
                                <td>
                                    @if($menu->status === 'rejected' && $menu->rejection_reason)
                                        <span class="text-danger small">{{ $menu->rejection_reason }}</span>
                                    @elseif($menu->status === 'approved')
                                        <span class="text-success small">Siap digunakan untuk menu harian.</span>
                                    @else
                                        <span class="text-muted small">Menunggu review admin.</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <button class="btn btn-outline-secondary btn-sm detail-menu-btn"
                                            data-menu='@json($menu)'>Detail</button>
                                    @if($menu->status === 'pending')
                                        <form action="{{ route('ajukan.cancel', $menu) }}" method="POST" class="d-inline-block" onsubmit="return confirm('Batalkan pengajuan menu ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-outline-danger btn-sm" type="submit">Batalkan</button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>

<!-- Modal detail menu -->
<div class="modal fade" id="sppgMenuDetail" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detail Menu</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <h5 id="sppgDetailTitle" class="h5 mb-1"></h5>
                    <p class="text-muted small mb-0" id="sppgDetailStatus"></p>
                </div>
                <p id="sppgDetailDescription"></p>

                <div class="mt-3" id="sppgDetailItems"></div>
                <div class="mt-3" id="sppgDetailNutrition"></div>
            </div>
        </div>
    </div>
</div>

<script>
let index = 1;

document.getElementById('add-item').addEventListener('click', function () {
    let container = document.getElementById('menu-items');

    let html = `
    <div class="menu-item border rounded p-3 mb-3">
        <div class="row g-2">

            <div class="col-md-4">
                <label class="form-label">Nama Item</label>
                <input type="text" name="items[${index}][name]" class="form-control" required>
            </div>

            <div class="col-md-3">
                <label class="form-label">Porsi</label>
                <input type="text" name="items[${index}][portion]" class="form-control" required>
            </div>

            <div class="col-md-3">
                <label class="form-label">Kategori</label>
                <select name="items[${index}][category]" class="form-control" required>
                    <option value="carbs">Karbohidrat</option>
                    <option value="protein">Protein</option>
                    <option value="vegetable">Sayur</option>
                    <option value="fruit">Buah</option>
                    <option value="drink">Minuman</option>
                    <option value="other">Lainnya</option>
                </select>
            </div>

        </div>
    </div>
    `;

    container.insertAdjacentHTML('beforeend', html);
    index++;
});

document.querySelectorAll('.detail-menu-btn').forEach(button => {
    button.addEventListener('click', () => {
        const data = JSON.parse(button.dataset.menu);

        document.getElementById('sppgDetailTitle').textContent = data.title;
        document.getElementById('sppgDetailStatus').textContent = `Status: ${data.status}`;
        document.getElementById('sppgDetailDescription').textContent = data.description || '-';

        const itemsContainer = document.getElementById('sppgDetailItems');
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

        const nutritionContainer = document.getElementById('sppgDetailNutrition');
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

        bootstrap.Modal.getOrCreateInstance(document.getElementById('sppgMenuDetail')).show();
    });
});
</script>
@endsection
