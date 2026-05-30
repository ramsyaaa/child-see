@extends('superadmin.layout.master')
@section('page-title', 'Aturan Penilaian')

@section('page-banner')
<div class="inklu-page-banner">
    <div>
        <ul class="inklu-breadcrumb">
            <li><a href="{{ route('superadmin.dashboard') }}"><i class="ti ti-home-2"></i></a></li>
            <li class="active">Aturan Penilaian</li>
        </ul>
        <h2>Aturan Penilaian</h2>
    </div>
    <div class="inklu-banner-actions">
        <a href="{{ route('superadmin.rules.create') }}" class="btn-banner"><i class="ti ti-plus"></i> Tambah Aturan</a>
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
@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show">
        <i class="ti ti-alert-circle me-2"></i>{{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="card">
    <div class="card-header"><h5 class="mb-0" style="color:#4A3769;">Daftar Aturan Penilaian</h5></div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Kategori</th>
                        <th>Domain</th>
                        <th>Label</th>
                        <th>Tingkat Keparahan</th>
                        <th>Rentang Skor</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($rules as $rule)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>
                            @if($rule->category)
                                <span class="badge bg-light text-dark border">{{ $rule->category->name }}</span>
                            @else<span class="text-muted">—</span>@endif
                        </td>
                        <td>
                            @if($rule->domain)
                                {{ $rule->domain->name }}
                            @else
                                <span class="text-muted fst-italic">Keseluruhan</span>
                            @endif
                        </td>
                        <td><strong>{{ $rule->label }}</strong></td>
                        <td>
                            @php
                                $severityMap = [
                                    'normal' => ['color'=>'#839986','label'=>'Belum Terindikasi'],
                                    'light'  => ['color'=>'#8D77AB','label'=>'Terindikasi Ringan'],
                                    'medium' => ['color'=>'#A86916','label'=>'Terindikasi Sedang'],
                                    'heavy'  => ['color'=>'#dc3545','label'=>'Terindikasi Kuat'],
                                ];
                                $sv = $severityMap[$rule->severity_level] ?? ['color'=>'#6c757d','label'=>$rule->severity_level];
                            @endphp
                            <span class="badge" style="background:{{ $sv['color'] }};">{{ $sv['label'] }}</span>
                        </td>
                        <td>
                            <span class="badge bg-light text-dark border">
                                {{ $rule->min_score }} – {{ $rule->max_score }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('superadmin.rules.edit', $rule) }}" class="btn btn-sm btn-outline-primary me-1">
                                <i class="ti ti-edit"></i>
                            </a>
                            <form action="{{ route('superadmin.rules.destroy', $rule) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger"
                                    onclick="return confirm('Hapus aturan ini?')">
                                    <i class="ti ti-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-5 text-muted">
                            <i class="ti ti-ruler-off" style="font-size:2.5rem;opacity:.25;display:block;margin-bottom:.5rem;"></i>
                            Belum ada aturan penilaian.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection