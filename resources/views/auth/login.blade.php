@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center align-items-center min-vh-100">
        <div class="col-md-6 col-lg-4">
            <div class="text-center mb-4">
                <h2 class="fw-bold">Login</h2>
            </div>

            @if($errors->any())
                <div class="alert alert-danger">
                    @foreach($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="mb-3">
                @csrf
                <div class="mb-3">
                    <input type="email" 
                           class="form-control" 
                           name="email" 
                           placeholder="Email" 
                           required 
                           autofocus>
                </div>
                <div class="mb-3">
                    <input type="password" 
                           class="form-control" 
                           name="password" 
                           placeholder="Password" 
                           required>
                </div>
                <button type="submit" class="btn btn-primary w-100">
                    Masuk
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
