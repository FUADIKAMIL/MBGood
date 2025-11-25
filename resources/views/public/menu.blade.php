<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
</head>
<body>

@extends('layouts.app')

@section('content')

<div class="text-center mb-5">
    <h1 class="fw-bold">Monitoring Makanan Bergizi Gratis (MBG)</h1>
    <p class="text-secondary">Pantau penyediaan dan kualitas menu sekolah di seluruh Indonesia</p>
</div>

<div class="row">
    @foreach($schools as $school)
    <div class="col-md-4">
        <div class="card border-0 shadow-sm mb-4 p-2" style="border-radius: 12px;">
            <div class="card-body">

                <h5 class="fw-bold">{{ $school->name }}</h5>

                <p class="text-secondary" style="font-size: 14px;">
                    <i class="bi bi-geo-alt-fill text-danger"></i>
                    {{ $school->region }} <br>
                    <i class="bi bi-house-fill text-primary"></i>
                    {{ $school->address }}
                </p>

                <a href="/school/{{ $school->id }}" 
                   class="btn btn-primary w-100" 
                   style="border-radius: 8px;">
                   Lihat Menu
                </a>

            </div>
        </div>
    </div>
    @endforeach
</div>

@endsection

</body>
</html>