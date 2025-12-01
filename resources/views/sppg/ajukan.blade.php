@extends('layouts.app')

@section('content')
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
</script>
@endsection
