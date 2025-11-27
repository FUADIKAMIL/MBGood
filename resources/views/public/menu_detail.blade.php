@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <!-- Header Menu -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body">
                    <nav aria-label="breadcrumb" class="mb-3">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none">Beranda</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('schools.show', $menu->school_id) }}" class="text-decoration-none">{{ $menu->school->name }}</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Menu</li>
                        </ol>
                    </nav>
                    
                    <h1 class="h3 fw-bold mb-1">{{ $menu->title }}</h1>
                    <p class="text-muted small mb-3">Disajikan di {{ $menu->school->name }}</p>
                    
                    <div class="d-flex flex-wrap align-items-center gap-3 mb-3">
                        <div class="d-flex align-items-center gap-2">
                            <span class="badge bg-primary bg-opacity-10 text-primary">
                                Vendor: {{ $menu->vendor->company_name }}
                            </span>
                        </div>
                        @if($menu->portion)
                        <span class="badge bg-primary bg-opacity-10 text-primary">
                            {{ $menu->portion }}
                        </span>
                        @endif
                        <span class="text-muted small">
                            <i class="bi bi-calendar3 me-1"></i> {{ $menu->created_at->format('d M Y') }}
                        </span>
                    </div>
                    
                    @if($menu->image)
                        <img src="{{ $menu->image }}" class="img-fluid rounded-3 mb-3" alt="{{ $menu->title }}">
                    @endif
                    
                    <div class="mb-4">
                        <h5 class="fw-bold mb-2">Deskripsi</h5>
                        <p class="mb-0 text-muted">{{ $menu->description ?? 'Tidak ada deskripsi tersedia.' }}</p>
                    </div>
                    
                    <!-- Daftar Item Makanan -->
                    @if($menu->items && $menu->items->count() > 0)
                        <div class="card border-0 shadow-sm mb-4">
                            <div class="card-body p-0">
                                <div class="px-3 pt-3 pb-2">
                                    <h5 class="fw-bold mb-0">Daftar Item Makanan</h5>
                                </div>
                                <div class="list-group list-group-flush">
                                    @foreach($menu->items as $item)
                                        <div class="list-group-item d-flex justify-content-between align-items-start">
                                            <div>
                                                <div class="fw-medium">{{ $item->name }}</div>
                                                @if($item->category)
                                                    <div class="text-muted small">{{ $item->category }}</div>
                                                @endif
                                            </div>
                                            @if($item->portion)
                                                <div class="text-end small fw-semibold text-muted">
                                                    {{ $item->portion }}
                                                </div>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif
                    
                    <!-- Nilai Gizi -->
                    @if($menu->nutrition)
                        <div class="card border-0 shadow-sm mb-4">
                            <div class="card-body p-0">
                                <div class="px-3 pt-3 pb-2">
                                    <h5 class="fw-bold mb-0">Informasi Nilai Gizi</h5>
                                </div>
                                <div class="table-responsive mb-0">
                                    <table class="table mb-0">
                                        <tbody>
                                            <tr>
                                                <th class="border-top-0" style="width: 40%;">Kalori</th>
                                                <td class="border-top-0 text-end">{{ $menu->nutrition->calories ?? '0' }} kkal</td>
                                            </tr>
                                            <tr>
                                                <th>Protein</th>
                                                <td class="text-end">{{ $menu->nutrition->protein ?? '0' }} g</td>
                                            </tr>
                                            <tr>
                                                <th>Lemak</th>
                                                <td class="text-end">{{ $menu->nutrition->fat ?? '0' }} g</td>
                                            </tr>
                                            <tr>
                                                <th>Karbohidrat</th>
                                                <td class="text-end">{{ $menu->nutrition->carbs ?? '0' }} g</td>
                                            </tr>
                                            <tr>
                                                <th>Vitamin</th>
                                                <td class="text-end">
                                                    @if(is_array($menu->nutrition->vitamins))
                                                        {{ implode(', ', $menu->nutrition->vitamins) }}
                                                    @else
                                                        {{ $menu->nutrition->vitamins ?? '-' }}
                                                    @endif
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
            
            <!-- Komentar Publik -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body">
                    <h5 class="fw-bold mb-1">Komentar Publik</h5>
                    <p class="text-muted small mb-3">Pendapat orang tua dan pengunjung mengenai menu ini.</p>
                    @if($comments->count() > 0)
                        <div class="list-group list-group-flush">
                            @foreach($comments as $comment)
                                <div class="list-group-item border-0 px-0 py-3">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <span class="fw-semibold">{{ $comment->user_name }}</span>
                                        <small class="text-muted">{{ $comment->created_at->diffForHumans() }}</small>
                                    </div>
                                    <div class="text-muted">{{ $comment->body }}</div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-3 text-muted small">Belum ada komentar</div>
                    @endif
                </div>
            </div>

            <!-- Tulis Komentar -->
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h5 class="fw-bold mb-3">Tulis Komentar</h5>
                    <form action="{{ route('comment.store', $menu->id) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label small text-muted mb-1">Nama</label>
                            <input type="text" name="name" class="form-control" placeholder="Nama kamu" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small text-muted mb-1">Komentar</label>
                            <textarea class="form-control" name="body" rows="3" placeholder="Tulis komentar.." required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary btn-sm">Kirim Komentar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection