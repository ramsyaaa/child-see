@extends('member.layout.master')
@section('title', 'Profil Anak')
@section('page-title', 'Profil Anak')

@section('content')

@if(session('success'))
  <div class="mb-5 p-4 rounded-xl text-sm font-medium flex items-center gap-3" style="background:#dcfce7;color:#166534;border:1px solid #bbf7d0">
    <i class="fas fa-check-circle"></i> {{ session('success') }}
  </div>
@endif
@if(session('info'))
  <div class="mb-5 p-4 rounded-xl text-sm font-medium flex items-center gap-3" style="background:#eff6ff;color:#1d4ed8;border:1px solid #bfdbfe">
    <i class="fas fa-info-circle"></i> {{ session('info') }}
  </div>
@endif

@if($children->isEmpty())

  {{-- No child yet --}}
  <div class="dash-card text-center py-16">
    <div class="w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-4" style="background:#F0EEF5">
      <i class="fas fa-child text-[#BAA6D6] text-3xl"></i>
    </div>
    <h3 class="font-semibold text-[#2E2046] mb-2" style="font-family:'Playfair Display',serif">Belum Ada Data Anak</h3>
    <p class="text-sm text-gray-500 mb-6 max-w-xs mx-auto">Setiap akun dapat mendaftarkan satu data anak untuk dilakukan asesmen identifikasi.</p>
    <a href="{{ route('member.children.create') }}" class="btn-primary">
      <i class="fas fa-plus"></i> Tambah Data Anak
    </a>
  </div>

@else

  {{-- Toolbar --}}
  <div class="mb-5 flex flex-wrap items-center justify-between gap-3">
    <div class="flex flex-wrap gap-2">
      <a href="{{ route('member.export.children.excel') }}"
         class="inline-flex items-center gap-2 text-sm font-semibold px-4 py-2 rounded-xl transition-all"
         style="background:#1D6F42;color:#fff">
        <i class="fas fa-file-excel"></i> Export Excel
      </a>
      <a href="{{ route('member.export.children.pdf-zip') }}"
         class="inline-flex items-center gap-2 text-sm font-semibold px-4 py-2 rounded-xl transition-all"
         style="background:#dc3545;color:#fff">
        <i class="fas fa-file-archive"></i> Export PDF (ZIP)
      </a>
    </div>
    @if($childCount < $quota)
      <a href="{{ route('member.children.create') }}" class="btn-primary text-sm px-4 py-2">
        <i class="fas fa-plus"></i> Tambah Anak
      </a>
    @endif
  </div>

  @foreach($children as $child)

  {{-- Profile card --}}
  <div class="dash-card mb-6">
    @if($children->count() > 1)
      <div class="text-xs font-semibold uppercase tracking-wider mb-4" style="color:#8499B6;">Anak {{ $loop->iteration }}</div>
    @endif
    <div class="flex flex-col sm:flex-row gap-6">
      {{-- Avatar --}}
      <div class="flex flex-col items-center gap-3 flex-shrink-0">
        @if($child->photo_url)
          <img src="{{ $child->photo_url }}" alt="{{ $child->full_name }}"
               class="w-20 h-20 rounded-2xl object-cover" style="border:2px solid rgba(186,166,214,.3)">
        @else
          <div class="w-20 h-20 rounded-2xl flex items-center justify-center text-white font-bold text-3xl" style="background:linear-gradient(135deg,#4A3769,#BAA6D6)">
            {{ strtoupper(substr($child->full_name, 0, 1)) }}
          </div>
        @endif
        <a href="{{ route('member.children.edit', $child) }}" class="btn-secondary text-xs px-4 py-2">
          <i class="fas fa-edit"></i> Edit Profil
        </a>
        <a href="{{ route('member.children.pdf', $child) }}" target="_blank"
           class="inline-flex items-center gap-1 text-xs font-semibold px-3 py-1.5 rounded-xl transition-all"
           style="background:#fee2e2;color:#991b1b;border:1px solid #fecaca">
          <i class="fas fa-file-pdf"></i> PDF
        </a>
      </div>

      {{-- Detail --}}
      <div class="flex-1 grid grid-cols-1 sm:grid-cols-2 gap-x-8 gap-y-3">
        <div>
          <p class="text-xs text-gray-400 uppercase tracking-wider mb-0.5">Nama Lengkap</p>
          <p class="text-sm font-semibold text-[#2E2046]">{{ $child->full_name }}</p>
        </div>
        @if($child->nick_name)
        <div>
          <p class="text-xs text-gray-400 uppercase tracking-wider mb-0.5">Nama Panggilan</p>
          <p class="text-sm font-semibold text-[#2E2046]">{{ $child->nick_name }}</p>
        </div>
        @endif
        @if($child->gender)
        <div>
          <p class="text-xs text-gray-400 uppercase tracking-wider mb-0.5">Jenis Kelamin</p>
          <p class="text-sm font-semibold text-[#2E2046]">{{ $child->gender === 'male' ? 'Laki-laki' : 'Perempuan' }}</p>
        </div>
        @endif
        @if($child->birth_date)
        <div>
          <p class="text-xs text-gray-400 uppercase tracking-wider mb-0.5">Tanggal Lahir</p>
          <p class="text-sm font-semibold text-[#2E2046]">{{ \Carbon\Carbon::parse($child->birth_date)->translatedFormat('d F Y') }} <span class="text-gray-400 font-normal">({{ \Carbon\Carbon::parse($child->birth_date)->age }} tahun)</span></p>
        </div>
        @endif
        @if($child->school_name)
        <div>
          <p class="text-xs text-gray-400 uppercase tracking-wider mb-0.5">Sekolah</p>
          <p class="text-sm font-semibold text-[#2E2046]">{{ $child->school_name }}</p>
        </div>
        @endif
        @if($child->class_name)
        <div>
          <p class="text-xs text-gray-400 uppercase tracking-wider mb-0.5">Kelas</p>
          <p class="text-sm font-semibold text-[#2E2046]">{{ $child->class_name }}</p>
        </div>
        @endif
        @if($child->parent_name)
        <div>
          <p class="text-xs text-gray-400 uppercase tracking-wider mb-0.5">Nama Orang Tua / Wali</p>
          <p class="text-sm font-semibold text-[#2E2046]">{{ $child->parent_name }}</p>
        </div>
        @endif
        @if($child->parent_phone)
        <div>
          <p class="text-xs text-gray-400 uppercase tracking-wider mb-0.5">No. HP Orang Tua</p>
          <p class="text-sm font-semibold text-[#2E2046]">{{ $child->parent_phone }}</p>
        </div>
        @endif
        @if($child->address)
        <div class="sm:col-span-2">
          <p class="text-xs text-gray-400 uppercase tracking-wider mb-0.5">Alamat</p>
          <p class="text-sm font-semibold text-[#2E2046]">{{ $child->address }}</p>
        </div>
        @endif
        @if($child->notes)
        <div class="sm:col-span-2">
          <p class="text-xs text-gray-400 uppercase tracking-wider mb-0.5">Catatan</p>
          <p class="text-sm text-gray-600">{{ $child->notes }}</p>
        </div>
        @endif
      </div>
    </div>
  </div>

  {{-- Assessment history for this child --}}
  <div class="dash-card mb-4">
    <div class="flex items-center justify-between mb-5">
      <h3 class="font-semibold text-[#2E2046]" style="font-family:'Playfair Display',serif">Riwayat Asesmen</h3>
      <a href="{{ route('member.assessment.start') }}" class="btn-primary text-sm px-4 py-2">
        <i class="fas fa-play-circle"></i> Asesmen Baru
      </a>
    </div>

    @forelse($child->assessments as $assessment)
      @php
        $severityColors = ['normal'=>'#839986','light'=>'#8D77AB','medium'=>'#A86916','heavy'=>'#dc3545'];
        $color = $severityColors[$assessment->severity_level] ?? '#6b7280';
      @endphp
      <a href="{{ route('member.assessment.result', $assessment) }}" class="block py-3 border-b last:border-0 hover:bg-gray-50 -mx-6 px-6 transition-colors" style="border-color:rgba(186,166,214,.15)">
        <div class="flex items-center justify-between gap-2">
          <div class="flex-1 min-w-0">
            <div class="flex items-center gap-2 mb-0.5">
              <span class="text-xs font-mono font-semibold text-[#4A3769]">{{ $assessment->assessment_code }}</span>
              <span class="text-xs px-2 py-0.5 rounded-full font-semibold text-white" style="background:{{ $color }}">{{ $assessment->result_label }}</span>
            </div>
            <p class="text-sm text-gray-600">{{ $assessment->category->name ?? '-' }}</p>
          </div>
          <div class="text-right flex-shrink-0">
            <p class="text-xs text-gray-400">{{ $assessment->created_at->format('d M Y') }}</p>
            <i class="fas fa-chevron-right text-gray-300 text-xs mt-1"></i>
          </div>
        </div>
      </a>
    @empty
      <div class="text-center py-10">
        <p class="text-sm text-gray-500 mb-4">Belum ada asesmen untuk anak ini.</p>
        <a href="{{ route('member.assessment.start') }}" class="btn-primary text-sm">
          <i class="fas fa-play-circle"></i> Mulai Asesmen Pertama
        </a>
      </div>
    @endforelse
  </div>

  {{-- Danger zone --}}
  <div class="dash-card mb-8" style="border-color:rgba(220,38,38,.2);">
    <h4 class="text-sm font-semibold text-red-700 mb-2">Hapus Data Anak</h4>
    <p class="text-xs text-gray-500 mb-4">Menghapus data anak akan menghapus juga seluruh riwayat asesmen. Tindakan ini tidak dapat dibatalkan.</p>
    <form action="{{ route('member.children.destroy', $child) }}" method="POST"
          onsubmit="return confirm('Hapus data {{ addslashes($child->full_name) }}? Semua riwayat asesmen juga akan dihapus.')">
      @csrf @method('DELETE')
      <button type="submit" class="inline-flex items-center gap-2 text-sm px-4 py-2 rounded-xl font-semibold" style="background:#fee2e2;color:#991b1b;border:1px solid #fecaca">
        <i class="fas fa-trash"></i> Hapus Data Anak
      </button>
    </form>
  </div>

  @endforeach

@endif

@endsection
