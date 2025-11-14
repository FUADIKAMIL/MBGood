@extends('layouts.app')

@section('content')

{{-- Header Menu --}}
<div class="mb-4">
    <h2 class="fw-bold">{{ $menu->title }}</h2>

    <p class="text-secondary">
        <i class="bi bi-building"></i> Vendor: <strong>{{ $menu->vendor->company_name }}</strong> <br>
        <i class="bi bi-house"></i> Sekolah: <strong>{{ $menu->school->name }}</strong> <br>
        <i class="bi bi-clock-history"></i> Dibuat: {{ $menu->created_at->format('d M Y') }}
    </p>

    <span class="badge 
        @if($menu->status == 'approved') bg-success
        @elseif($menu->status == 'pending') bg-warning text-dark
        @elseif($menu->status == 'revision') bg-info text-dark
        @else bg-danger
        @endif
        px-3 py-2"
        style="font-size:14px; border-radius: 8px;"
    >
        Status: {{ ucfirst($menu->status) }}
    </span>
</div>


{{-- Deskripsi Menu --}}
<div class="card mb-4 shadow-sm border-0" style="border-radius: 12px;">
    <div class="card-body">
        <h5 class="fw-bold mb-3">Deskripsi Menu</h5>
        <p class="text-secondary">{{ $menu->description }}</p>
    </div>
</div>


{{-- Daftar Item Makanan --}}
<div class="card mb-4 shadow-sm border-0" style="border-radius: 12px;">
    <div class="card-body">
        <h5 class="fw-bold mb-3">Daftar Item Makanan</h5>

        @if($menu->items->count() == 0)
            <p class="text-danger">Belum ada item makanan.</p>
        @else
            <ul class="list-group">
                @foreach($menu->items as $item)
                <li class="list-group-item d-flex justify-content-between">
                    <span>{{ $item->name }} <br>
                        <small class="text-secondary">{{ ucfirst($item->category) }}</small>
                    </span>
                    <strong>{{ $item->portion }}</strong>
                </li>
                @endforeach
            </ul>
        @endif
    </div>
</div>


{{-- Nilai Gizi --}}
<div class="card mb-4 shadow-sm border-0" style="border-radius: 12px;">
    <div class="card-body">
        <h5 class="fw-bold mb-3">Informasi Nilai Gizi</h5>

        @if(!$menu->nutrition)
            <p class="text-danger">Belum ada data gizi.</p>
        @else
        <table class="table table-bordered">
            <tbody>
                <tr>
                    <th>Kalori</th>
                    <td>{{ $menu->nutrition->calories }} kcal</td>
                </tr>
                <tr>
                    <th>Protein</th>
                    <td>{{ $menu->nutrition->protein }} g</td>
                </tr>
                <tr>
                    <th>Lemak</th>
                    <td>{{ $menu->nutrition->fat }} g</td>
                </tr>
                <tr>
                    <th>Karbohidrat</th>
                    <td>{{ $menu->nutrition->carbs }} g</td>
                </tr>
                <tr>
                    <th>Vitamin</th>
                    <td>{{ implode(', ', $menu->nutrition->vitamins ?? []) }}</td>
                </tr>
            </tbody>
        </table>
        @endif
    </div>
</div>


{{-- Komentar Publik --}}
<div class="card mb-4 shadow-sm border-0" style="border-radius: 12px;">
    <div class="card-body">
        <h5 class="fw-bold mb-3">Komentar Publik</h5>

        @if($comments->count() == 0)
            <p class="text-secondary fst-italic">Belum ada komentar.</p>
        @else
            @foreach($comments as $comment)
            <div class="p-3 mb-3" style="background: #f8f9fa; border-radius: 10px;">
                <div class="d-flex justify-content-between">
                    <strong>{{ $comment->user_name }}</strong>
                    <small class="text-muted">{{ $comment->created_at->diffForHumans() }}</small>
                </div>
                <p class="text-secondary mb-0" style="font-size: 15px;">{{ $comment->body }}</p>
            </div>
            @endforeach
        @endif

    </div>
</div>

{{-- Form Tambah Komentar --}}
<div class="card shadow-sm border-0 mt-3" style="border-radius: 12px;">
    <div class="card-body">
        <h5 class="fw-bold mb-3">Tulis Komentar</h5>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <form action="/menu/{{ $menu->id }}/comment" method="POST">
            @csrf

            <div class="mb-3">
                <label class="form-label">Nama</label>
                <input type="text" name="name" class="form-control" placeholder="Nama kamu"
                       value="{{ old('name') }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Komentar</label>
                <textarea name="body" class="form-control" rows="3" placeholder="Tulis komentar..."
                          required>{{ old('body') }}</textarea>
            </div>

            <button class="btn btn-primary px-4">Kirim</button>
        </form>
    </div>
</div>

@endsection