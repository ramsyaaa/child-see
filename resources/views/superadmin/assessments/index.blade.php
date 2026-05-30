@extends('superadmin.layout.master')
@section('page-title', 'Hasil Asesmen')

@section('page-banner')
<div class="inklu-page-banner">
    <div>
        <ul class="inklu-breadcrumb">
            <li><a href="{{ route('superadmin.dashboard') }}"><i class="ti ti-home-2"></i></a></li>
            <li class="active">Hasil Asesmen</li>
        </ul>
        <h2>Hasil Asesmen</h2>
    </div>
</div>
@endsection

@section('content')

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        <i class="ti ti-circle-check me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

{{-- Filters --}}
<div class="card mb-3">
    <div class="card-body py-2">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-4">
                <label class="form-label small fw-semibold mb-1">Kategori</label>
                <select name="category_id" class="form-select form-select-sm" onchange="this.form.submit()">
                    <option value="">Semua Kategori</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" @selected(request('category_id') == $cat->id)>{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label small fw-semibold mb-1">Tingkat Keparahan</label>
                <select name="severity_level" class="form-select form-select-sm" onchange="this.form.submit()">
                    <option value="">Semua Tingkat</option>
                    <option value="normal" @selected(request('severity_level')=='normal')>Belum Terindikasi</option>
                    <option value="light"  @selected(request('severity_level')=='light')>Terindikasi Ringan</option>
                    <option value="medium" @selected(request('severity_level')=='medium')>Terindikasi Sedang</option>
                    <option value="heavy"  @selected(request('severity_level')=='heavy')>Terindikasi Kuat</option>
                </select>
            </div>
            <div class="col-md-2">
                <a href="{{ route('superadmin.assessments.index') }}" class="btn btn-sm btn-outline-secondary w-100">Reset</a>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header"><h5 class="mb-0" style="color:#4A3769;">Daftar Hasil Asesmen</h5></div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Kode</th>
                        <th>Anak</th>
                        <th>Pengguna</th>
                        <th>Kategori</th>
                        <th>Keparahan</th>
                        <th>Skor</th>
                        <th>Tanggal</th>
                        <th>Detail</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($assessments as $a)
                    @php
                        $sevColors = [
                            'normal'=>'#839986','light'=>'#8D77AB','medium'=>'#A86916','heavy'=>'#dc3545'
                        ];
                        $sevLabels = [
                            'normal'=>'Belum Terindikasi','light'=>'Terindikasi Ringan',
                            'medium'=>'Terindikasi Sedang','heavy'=>'Terindikasi Kuat'
                        ];
                        $svColor = $sevColors[$a->severity_level ?? ''] ?? ($a->color ?? '#6c757d');
                        $svLabel = $sevLabels[$a->severity_level ?? ''] ?? ($a->severity_level ?? '—');
                    @endphp
                    <tr>
                        <td>{{ $assessments->firstItem() + $loop->index }}</td>
                        <td><code class="small">{{ $a->assessment_code }}</code></td>
                        <td>
                            @if($a->child)
                                <strong>{{ $a->child->full_name }}</strong>
                                @if($a->child->birth_date)
                                    <br><small class="text-muted">{{ \Carbon\Carbon::parse($a->child->birth_date)->age }} thn</small>
                                @endif
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                        <td>
                            @if($a->user)
                                {{ $a->user->name }}
                                <br><small class="text-muted">{{ $a->user->email }}</small>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                        <td>
                            @if($a->category)
                                <span class="badge bg-light text-dark border">{{ $a->category->name }}</span>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge" style="background:{{ $svColor }};">{{ $svLabel }}</span>
                        </td>
                        <td><strong>{{ number_format($a->total_score ?? 0, 1) }}</strong></td>
                        <td>{{ $a->created_at ? $a->created_at->format('d M Y') : '—' }}</td>
                        <td>
                            <a href="{{ route('superadmin.assessments.show', $a) }}" class="btn btn-sm btn-outline-primary">
                                <i class="ti ti-eye"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center py-5 text-muted">
                            <i class="ti ti-clipboard-off" style="font-size:2.5rem;opacity:.25;display:block;margin-bottom:.5rem;"></i>
                            Belum ada data asesmen.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="d-flex justify-content-center mt-3">
            {{ $assessments->withQueryString()->links() }}
        </div>
    </div>
</div>

@endsection