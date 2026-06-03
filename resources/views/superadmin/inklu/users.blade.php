@extends('superadmin.layout.master')
@section('page-title', 'Pengguna')

@section('page-banner')
<div class="inklu-page-banner">
    <div>
        <ul class="inklu-breadcrumb">
            <li><a href="{{ route('superadmin.dashboard') }}"><i class="ti ti-home-2"></i></a></li>
            <li><a href="{{ route('superadmin.dashboard') }}">Child See</a></li>
            <li class="active">Pengguna</li>
        </ul>
        <h2>Pengguna</h2>
    </div>
</div>
@endsection

@section('content')

<div class="card" style="border:1.5px solid rgba(186,166,214,0.25);">
    <div class="card-header d-flex align-items-center justify-content-between flex-wrap gap-2" style="background:transparent;border-bottom:1px solid rgba(186,166,214,0.2);padding:1rem 1.25rem;">
        <h6 class="mb-0" style="color:#4A3769;">{{ $users->total() }} pengguna terdaftar</h6>
        <form method="GET" class="d-flex gap-2">
            <input name="search" value="{{ request('search') }}" placeholder="Cari nama / email…" class="form-control form-control-sm" style="width:220px;border-color:#BAA6D6;">
            <button class="btn btn-sm" style="background:#5C477F;color:#fff;">Cari</button>
        </form>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead style="background:rgba(186,166,214,0.07);">
                    <tr>
                        <th style="color:rgba(74,55,105,0.65);font-size:0.75rem;font-weight:600;text-transform:uppercase;padding:0.75rem 1.25rem;border:none;">Nama</th>
                        <th style="color:rgba(74,55,105,0.65);font-size:0.75rem;font-weight:600;text-transform:uppercase;border:none;">Email</th>
                        <th style="color:rgba(74,55,105,0.65);font-size:0.75rem;font-weight:600;text-transform:uppercase;border:none;">Jumlah Tes</th>
                        <th style="color:rgba(74,55,105,0.65);font-size:0.75rem;font-weight:600;text-transform:uppercase;border:none;">Bergabung</th>
                        <th style="color:rgba(74,55,105,0.65);font-size:0.75rem;font-weight:600;text-transform:uppercase;border:none;"></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $u)
                    <tr>
                        <td style="padding:0.75rem 1.25rem;color:#26223A;font-weight:500;">{{ $u->full_name ?? ($u->first_name . ' ' . $u->last_name) }}</td>
                        <td style="color:rgba(38,34,58,0.65);font-size:0.875rem;">{{ $u->email }}</td>
                        <td>
                            <span style="background:rgba(92,71,127,0.10);color:#5C477F;padding:2px 10px;border-radius:20px;font-size:0.8rem;font-weight:600;">
                                {{ $u->identification_results_count }} tes
                            </span>
                        </td>
                        <td style="font-size:0.8rem;color:rgba(38,34,58,0.55);">{{ $u->created_at->format('d M Y') }}</td>
                        <td>
                            <a href="{{ route('superadmin.inklu.users.show', $u) }}" style="color:#5C477F;font-size:0.8rem;">Detail →</a>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="text-center py-4" style="color:rgba(74,55,105,0.40);">Tidak ada pengguna ditemukan.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer" style="background:transparent;border-top:1px solid rgba(186,166,214,0.2);">
        {{ $users->links() }}
    </div>
</div>
@endsection
