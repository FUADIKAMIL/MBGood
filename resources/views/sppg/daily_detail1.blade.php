@extends('layouts.app')

@section('content')
<div class="container py-4">

    <h3 class="fw-bold mb-3">Detail Menu Harian (Vendor)</h3>

    <div class="card mb-4">
        <div class="card-body">

            <h4>{{ $daily->menu->title }}</h4>
            <p class="text-muted">Tanggal: 
                <strong>{{ \Carbon\Carbon::parse($daily->date)->format('d M Y') }}</strong>
            </p>

            <h5 class="mt-3 fw-bold">Item</h5>
            <ul>
                @foreach($daily->menu->items as $item)
                    <li>{{ $item->name }} ({{ $item->portion }})</li>
                @endforeach
            </ul>

            <h5 class="mt-3 fw-bold">Gizi</h5>
            <p>Kalori: {{ $daily->menu->nutrition->calories }}</p>

        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <h5 class="fw-bold mb-3">Komentar Publik</h5>

            @foreach($daily->comments as $comment)
                <div class="border rounded p-2 mb-3">
                    <strong>{{ $comment->user_name }}</strong>
                    <p class="mb-1">{{ $comment->body }}</p>

                    @if($comment->reply)
                        <div class="ms-3 text-primary">
                            <strong>Balasan Vendor:</strong> {{ $comment->reply }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('sppg.comment.reply', $comment->id) }}" class="mt-2">
                        @csrf
                        <textarea name="reply" class="form-control mb-1" rows="2" required></textarea>
                        <button class="btn btn-sm btn-primary">Kirim Balasan</button>
                    </form>
                </div>
            @endforeach

        </div>
    </div>

</div>
@endsection