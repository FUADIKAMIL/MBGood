@if($schools->isEmpty())
    <p class="text-muted small mb-0">Belum ada sekolah terdaftar.</p>
@else
    <div class="table-responsive">
        <table class="table table-sm align-middle">
            <thead>
                <tr class="small text-muted">
                    <th style="width:40px;">#</th>
                    <th>Nama</th>
                    <th>Alamat</th>
                    <th style="width:200px;">Penanggung Jawab</th>
                    <th class="text-end" style="width:110px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($schools as $school)
                    @php
                        $vendor = $school->vendors->first();
                    @endphp
                    <tr class="small">
                        <td>{{ $schools->firstItem() + $loop->index }}</td>
                        <td>
                            <div class="fw-semibold">{{ $school->name }}</div>
                            <div class="text-muted">{{ $school->region }}</div>
                        </td>
                        <td>{{ $school->address }}</td>
                        <td>
                            @if($vendor)
                                <div class="fw-semibold">{{ $vendor->user->name ?? $vendor->company_name }}</div>
                                <div class="text-muted">{{ $vendor->contact ?? '-' }}</div>
                            @else
                                <span class="badge text-bg-light text-secondary">Belum ditentukan</span>
                            @endif
                        </td>
                        <td class="text-end">
                            <button class="btn btn-outline-primary btn-sm px-2 py-1 d-inline-flex align-items-center gap-1 edit-school-btn"
                                    data-update-url="{{ route('admin.schools.update', $school) }}"
                                    data-delete-url="{{ route('admin.schools.destroy', $school) }}"
                                    data-name="{{ e($school->name) }}"
                                    data-region="{{ e($school->region) }}"
                                    data-address="{{ e($school->address) }}"
                                    data-vendor-id="{{ optional($vendor)->id }}">
                                <i class="bi bi-pencil-square"></i>
                                <span>Edit</span>
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    @if($schools->hasPages())
        <div class="d-flex justify-content-center mt-3 ajax-pagination">
            {{ $schools->onEachSide(1)->links() }}
        </div>
    @endif
@endif
