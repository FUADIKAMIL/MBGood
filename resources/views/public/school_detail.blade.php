@extends('layouts.app')

@section('content')

<div class="mb-4">
    <h2 class="fw-bold">{{ $school->name }}</h2>
    <p class="text-secondary">
        <i class="bi bi-geo-alt-fill text-danger"></i> {{ $school->region }} <br>
        <i class="bi bi-house-fill text-primary"></i> {{ $school->address }}
    </p>
</div>

<h4 class="fw-bold mb-3">Menu MBG</h4>

@if($menus->count() == 0)
<div class="alert alert-warning">
    Belum ada menu yang tersedia untuk sekolah ini.
</div>
@endif

@foreach($menus as $menu)
<div class="card border-0 shadow-sm mb-3" style="border-radius: 10px;">
    <div class="card-body">

        <h5 class="fw-bold">{{ $menu->title }}</h5>
        <p class="text-secondary">{{ $menu->description }}</p>

        <span class="badge 
            @if($menu->status == 'approved') bg-success
            @elseif($menu->status == 'pending') bg-warning text-dark
            @elseif($menu->status == 'revision') bg-info text-dark
            @else bg-danger
            @endif
            px-3 py-2 position-absolute top-0 end-0 m-3" 
            style="font-size:14px; border-radius: 9px;"
        >
            {{ ucfirst($menu->status) }}
        </span>

        <a href="/menu/{{ $menu->id }}" 
           class="btn btn-outline-primary py-1"
           style="border-radius: 10px;">
           Detail Menu
        </a>

    </div>
</div>
@endforeach

@endsection