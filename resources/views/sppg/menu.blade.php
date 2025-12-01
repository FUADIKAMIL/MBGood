@extends('layouts.app')

@section('content')
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <h5 class="card-title mb-3">
            <i class="bi bi-calendar2-check text-primary"></i> Input Menu Harian
        </h5>

        <form action="{{ route('sppg.input.menu') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label class="form-label fw-semibold">Pilih Sekolah</label>
                <select name="school_id" class="form-control" required>
                    <option value="">-- Pilih Sekolah --</option>
                    @foreach($schools as $school)
                        <option value="{{ $school->id }}">{{ $school->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Tanggal</label>
                <input type="date" name="date" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Pilih Menu (Approved)</label>
                <select name="menu_id" class="form-control" required>
                    <option value="">-- Menu yang Sudah Disetujui Admin --</option>
                    @foreach($approvedMenus as $menu)
                        <option value="{{ $menu->id }}">
                            {{ $menu->title }}
                        </option>
                    @endforeach
                </select>
            </div>

            <button class="btn btn-success">
                <i class="bi bi-check-circle"></i> Simpan Menu Harian
            </button>
        </form>
    </div>
</div>
@endsection