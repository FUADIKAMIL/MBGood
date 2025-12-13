@extends('layouts.app')

@section('content')
@php
    $menuData = $menu ?? ($daily->menu ?? null);
@endphp

<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">

            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body">

                    <!-- Breadcrumb -->
                    <nav aria-label="breadcrumb" class="mb-3">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Beranda</a></li>

                            @isset($daily)
                                <li class="breadcrumb-item">
                                    <a href="{{ route('schools.show', $daily->school_id) }}">
                                        {{ $daily->school->name }}
                                    </a>
                                </li>
                            @else
                                <li class="breadcrumb-item">
                                    <a href="#">
                                        {{ $menuData->vendor->schools->first()->name ?? 'Tidak terkait sekolah' }}
                                    </a>
                                </li>
                            @endisset

                            <li class="breadcrumb-item active">Menu</li>
                        </ol>
                    </nav>

                    <!-- Title -->
                    <h1 class="h3 fw-bold">{{ $menuData->title }}</h1>

                    @isset($daily)
                        <p class="text-muted small">
                            Disajikan pada <strong>{{ \Carbon\Carbon::parse($daily->date)->format('d M Y') }}</strong>
                        </p>
                    @endisset

                    <p class="text-muted small">Vendor: {{ $menuData->vendor->company_name }}</p>

                    <!-- Description -->
                    @if($menuData->description)
                        <h5 class="fw-bold mt-3">Deskripsi</h5>
                        <p class="text-muted">{{ $menuData->description }}</p>
                    @endif

                    <!-- Items -->
                    @if($menuData->items->count())
                        <h5 class="fw-bold mt-4">Daftar Item Makanan</h5>
                        <ul class="list-group">
                            @foreach($menuData->items as $item)
                                <li class="list-group-item d-flex justify-content-between">
                                    <div>
                                        <strong>{{ $item->name }}</strong><br>
                                        <small class="text-muted">{{ $item->category }}</small>
                                    </div>
                                    <span class="text-muted small">{{ $item->portion }}</span>
                                </li>
                            @endforeach
                        </ul>
                    @endif

                    <!-- Nutrition -->
                    @if($menuData->nutrition)
                        <h5 class="fw-bold mt-4">Nilai Gizi</h5>
                        <table class="table">
                            <tr><th>Kalori</th><td>{{ $menuData->nutrition->calories }} kkal</td></tr>
                            <tr><th>Protein</th><td>{{ $menuData->nutrition->protein }} g</td></tr>
                            <tr><th>Lemak</th><td>{{ $menuData->nutrition->fat }} g</td></tr>
                            <tr><th>Karbohidrat</th><td>{{ $menuData->nutrition->carbs }} g</td></tr>
                            <tr><th>Vitamin</th><td>{{ implode(', ', $menuData->nutrition->vitamins ?? []) }}</td></tr>
                        </table>
                    @endif

                    <!-- COMMENTS (public) -->
                    @isset($daily)
                        <div class="card shadow-sm">
                            <div class="card-body">
                                <h5 class="fw-bold mb-3">Komentar Publik</h5>

                                @php
                                    $parentComments = $daily->comments->whereNull('parent_id');
                                @endphp

                                @if($parentComments->isEmpty())
                                    <p class="text-muted small mb-0">Belum ada komentar.</p>
                                @else
                                    @foreach($parentComments as $comment)
                                        <div class="border rounded p-3 mb-3">
                                            <strong>{{ $comment->user_name }}</strong>
                                            <p class="mb-1">{{ $comment->body }}</p>

                                            @if($comment->replies && $comment->replies->count())
                                                <div class="ms-3 mt-2 border-start ps-3">
                                                    @foreach($comment->replies as $reply)
                                                        <div class="mb-2">
                                                            <strong class="text-primary">{{ $reply->user_name }}</strong>
                                                            <p class="mb-0 small">{{ $reply->body }}</p>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @endif

                                            <form method="POST" action="{{ route('sppg.comment.reply', $comment->id) }}" class="mt-3">
                                                @csrf
                                                <textarea name="reply" class="form-control mb-2" rows="2" required placeholder="Balas komentar..."></textarea>
                                                <button class="btn btn-sm btn-primary">Kirim Balasan</button>
                                            </form>
                                        </div>
                                    @endforeach
                                @endif

                            </div>
                        </div>
                    @endisset

                </div>
            </div>

        </div>
    </div>
</div>
@endsection
