@extends('member.layout.master')
@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')

{{-- Flash --}}
@if(session('success'))
  <div class="mb-5 p-4 rounded-xl text-sm font-medium flex items-center gap-3" style="background:#dcfce7;color:#166534;border:1px solid #bbf7d0">
    <i class="fas fa-check-circle"></i> {{ session('success') }}
  </div>
@endif

{{-- Welcome + child card --}}
<div class="rounded-2xl p-6 mb-6 text-white relative overflow-hidden" style="background:linear-gradient(135deg,#2E2046,#5C477F)">
  <div class="relative z-10 flex flex-col sm:flex-row sm:items-center gap-4">
    <div class="flex-1">
      <p class="text-white/60 text-sm mb-1">Selamat datang,</p>
      <h1 class="text-2xl font-semibold mb-1" style="font-family:'Playfair Display',serif">
        {{ Auth::user()->full_name ?? Auth::user()->name }}
      </h1>
      @if($child)
        <p class="text-white/70 text-sm">Memantau perkembangan <strong class="text-white">{{ $child->full_name }}</strong></p>
      @else
        <p class="text-white/70 text-sm">Tambahkan data anak untuk mulai melakukan asesmen.</p>
      @endif
    </div>
    @if($child)
      <a href="{{ route('member.assessment.start') }}"
         class="flex-shrink-0 inline-flex items-center gap-2 bg-white text-[#4A3769] font-semibold px-5 py-3 rounded-xl text-sm hover:bg-[#F0EEF5] transition-all self-start sm:self-center">
        <i class="fas fa-play-circle"></i> Mulai Asesmen
      </a>
    @else
      <a href="{{ route('member.children.create') }}"
         class="flex-shrink-0 inline-flex items-center gap-2 bg-white text-[#4A3769] font-semibold px-5 py-3 rounded-xl text-sm hover:bg-[#F0EEF5] transition-all self-start sm:self-center">
        <i class="fas fa-plus"></i> Tambah Data Anak
      </a>
    @endif
  </div>
  <div class="absolute right-8 top-1/2 -translate-y-1/2 opacity-10 text-[120px] leading-none pointer-events-none">
    <i class="fas fa-eye"></i>
  </div>
</div>

@if($child)
  {{-- Child profile card --}}
  <div class="dash-card mb-6">
    <div class="flex flex-col sm:flex-row items-start sm:items-center gap-5">
      {{-- Avatar --}}
      <div class="w-16 h-16 rounded-2xl flex items-center justify-center text-white font-bold text-2xl flex-shrink-0" style="background:linear-gradient(135deg,#4A3769,#BAA6D6)">
        {{ strtoupper(substr($child->full_name, 0, 1)) }}
      </div>
      {{-- Info --}}
      <div class="flex-1 min-w-0">
        <div class="flex flex-wrap items-center gap-2 mb-1">
          <h2 class="text-xl font-semibold text-[#2E2046]" style="font-family:'Playfair Display',serif">{{ $child->full_name }}</h2>
          @if($child->nick_name)
            <span class="text-xs text-gray-400 font-normal">({{ $child->nick_name }})</span>
          @endif
        </div>
        <div class="flex flex-wrap gap-x-5 gap-y-1 text-xs text-gray-500">
          @if($child->school_name)
            <span><i class="fas fa-school mr-1 text-[#BAA6D6]"></i>{{ $child->school_name }}</span>
          @endif
          @if($child->class_name)
            <span><i class="fas fa-chalkboard mr-1 text-[#BAA6D6]"></i>Kelas {{ $child->class_name }}</span>
          @endif
          @if($child->birth_date)
            <span><i class="fas fa-birthday-cake mr-1 text-[#BAA6D6]"></i>{{ \Carbon\Carbon::parse($child->birth_date)->age }} tahun</span>
          @endif
          @if($child->gender)
            <span><i class="fas fa-{{ $child->gender === 'male' ? 'mars' : 'venus' }} mr-1 text-[#BAA6D6]"></i>{{ $child->gender === 'male' ? 'Laki-laki' : 'Perempuan' }}</span>
          @endif
        </div>
      </div>
      {{-- Stats + edit link --}}
      <div class="flex items-center gap-4 flex-shrink-0">
        <div class="text-center">
          <div class="text-2xl font-semibold text-[#4A3769]" style="font-family:'Playfair Display',serif">{{ $totalAssessments }}</div>
          <div class="text-xs text-gray-500 mt-0.5">Asesmen</div>
        </div>
        <a href="{{ route('member.children.edit', $child) }}" class="btn-secondary text-sm px-4 py-2">
          <i class="fas fa-edit"></i> Edit Profil
        </a>
      </div>
    </div>
  </div>
@else
  {{-- No child yet --}}
  <div class="dash-card mb-6 text-center py-14">
    <div class="w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-4" style="background:#F0EEF5">
      <i class="fas fa-child text-[#BAA6D6] text-3xl"></i>
    </div>
    <h3 class="font-semibold text-[#2E2046] mb-2" style="font-family:'Playfair Display',serif">Belum Ada Data Anak</h3>
    <p class="text-sm text-gray-500 mb-5 max-w-xs mx-auto">Tambahkan data anak untuk mulai melakukan asesmen identifikasi.</p>
    <a href="{{ route('member.children.create') }}" class="btn-primary">
      <i class="fas fa-plus"></i> Tambah Data Anak Sekarang
    </a>
  </div>
@endif

{{-- Recent assessments --}}
<div class="dash-card">
  <div class="flex items-center justify-between mb-5">
    <h3 class="font-semibold text-[#2E2046]" style="font-family:'Playfair Display',serif">Riwayat Asesmen Terbaru</h3>
    <a href="{{ route('member.assessment.history') }}" class="text-xs font-medium text-[#4A3769] hover:text-[#5C477F]">Lihat semua</a>
  </div>

  @forelse($recentAssessments as $assessment)
    @php
      $severityColors = ['normal'=>'#839986','light'=>'#8D77AB','medium'=>'#A86916','heavy'=>'#dc3545'];
      $color = $severityColors[$assessment->severity_level] ?? '#6b7280';
    @endphp
    <a href="{{ route('member.assessment.result', $assessment) }}" class="block py-3 border-b last:border-0 hover:bg-gray-50 -mx-6 px-6 transition-colors" style="border-color:rgba(186,166,214,.15)">
      <div class="flex items-center justify-between gap-2">
        <div class="flex-1 min-w-0">
          <div class="flex items-center gap-2 mb-0.5">
            <span class="text-xs font-mono font-semibold text-[#4A3769]">{{ $assessment->assessment_code }}</span>
            <span class="text-xs px-2 py-0.5 rounded-full font-semibold text-white" style="background:{{ $color }}">
              {{ $assessment->result_label }}
            </span>
          </div>
          <p class="text-sm text-gray-600 truncate">{{ $assessment->category->name ?? '-' }}</p>
        </div>
        <div class="text-right flex-shrink-0">
          <p class="text-xs text-gray-400">{{ $assessment->created_at->format('d M Y') }}</p>
          <i class="fas fa-chevron-right text-gray-300 text-xs mt-1"></i>
        </div>
      </div>
    </a>
  @empty
    <div class="text-center py-10">
      <div class="w-14 h-14 rounded-full flex items-center justify-center mx-auto mb-3" style="background:#F0EEF5">
        <i class="fas fa-clipboard-list text-[#BAA6D6] text-xl"></i>
      </div>
      <p class="text-sm text-gray-500 mb-4">Belum ada asesmen yang selesai.</p>
      @if($child)
        <a href="{{ route('member.assessment.start') }}" class="btn-primary text-sm">
          <i class="fas fa-play-circle"></i> Mulai Asesmen Pertama
        </a>
      @endif
    </div>
  @endforelse
</div>

@endsection
