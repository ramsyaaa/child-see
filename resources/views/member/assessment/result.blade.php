@extends('member.layout.master')
@section('title', 'Hasil Asesmen')
@section('page-title', 'Hasil Asesmen')

@section('content')

@php
  $severityColors = ['normal'=>'#839986','light'=>'#8D77AB','medium'=>'#A86916','heavy'=>'#dc3545'];
  $color = $severityColors[$assessment->severity_level] ?? ($assessment->color ?? '#4A3769');
@endphp

{{-- Result banner --}}
<div class="rounded-2xl p-6 mb-6 text-white relative overflow-hidden"
     style="background:linear-gradient(135deg,{{ $color }},{{ $color }}bb)">
  <div class="relative z-10">
    <div class="flex flex-wrap items-center gap-3 mb-3">
      <span class="font-mono text-sm font-semibold px-3 py-1 rounded-full" style="background:rgba(255,255,255,.2)">
        {{ $assessment->assessment_code }}
      </span>
      <span class="text-sm text-white/70">{{ $assessment->assessment_date?->format('d M Y') ?? $assessment->created_at->format('d M Y') }}</span>
    </div>
    <p class="text-white/70 text-sm mb-1">Hasil Asesmen — {{ $assessment->category->name ?? '-' }}</p>
    <h1 class="text-3xl font-bold mb-2" style="font-family:'Playfair Display',serif">
      {{ $assessment->result_label ?? 'Dalam Proses' }}
    </h1>
    <div class="flex flex-wrap gap-4 text-sm text-white/80">
      <span><i class="fas fa-child mr-2"></i>{{ $assessment->child->full_name ?? '-' }}</span>
      @if($assessment->total_score !== null)
        <span><i class="fas fa-chart-bar mr-2"></i>Skor: {{ $assessment->total_score }}</span>
      @endif
    </div>
  </div>
  <div class="absolute right-6 top-1/2 -translate-y-1/2 opacity-10 text-[100px] leading-none pointer-events-none">
    <i class="fas fa-clipboard-check"></i>
  </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

  {{-- Domain scores --}}
  <div class="lg:col-span-2 dash-card">
    <h3 class="font-semibold text-[#2E2046] mb-5" style="font-family:'Playfair Display',serif">Skor per Domain</h3>

    @if($assessment->domainScores && $assessment->domainScores->count())
      <div class="overflow-x-auto">
        <table class="w-full text-sm">
          <thead>
            <tr style="border-bottom:2px solid rgba(186,166,214,.2)">
              <th class="text-left pb-3 text-xs font-semibold uppercase tracking-wider text-gray-500">Domain</th>
              <th class="text-center pb-3 text-xs font-semibold uppercase tracking-wider text-gray-500">Skor</th>
              <th class="text-center pb-3 text-xs font-semibold uppercase tracking-wider text-gray-500">Hasil</th>
            </tr>
          </thead>
          <tbody>
            @foreach($assessment->domainScores as $ds)
              @php
                $dsColor = $severityColors[$ds->severity_level ?? ''] ?? '#6b7280';
              @endphp
              <tr class="border-b" style="border-color:rgba(186,166,214,.1)">
                <td class="py-3 text-[#2E2046] font-medium">{{ $ds->domain->name ?? '-' }}</td>
                <td class="py-3 text-center font-semibold text-[#4A3769]">{{ $ds->total_score ?? 0 }}</td>
                <td class="py-3 text-center">
                  <span class="text-xs px-2 py-0.5 rounded-full font-semibold text-white" style="background:{{ $dsColor }}">
                    {{ $ds->result_label ?? '-' }}
                  </span>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    @else
      <p class="text-sm text-gray-500">Data skor domain tidak tersedia.</p>
    @endif
  </div>

  {{-- Info & recommendation --}}
  <div class="space-y-4">
    <div class="dash-card">
      <h3 class="font-semibold text-[#2E2046] mb-4" style="font-family:'Playfair Display',serif">Informasi</h3>
      <div class="space-y-3 text-sm">
        <div class="flex justify-between gap-2">
          <span class="text-gray-500">Anak</span>
          <span class="font-medium text-[#2E2046] text-right">{{ $assessment->child->full_name ?? '-' }}</span>
        </div>
        <div class="flex justify-between gap-2">
          <span class="text-gray-500">Kategori</span>
          <span class="font-medium text-[#2E2046] text-right">{{ $assessment->category->name ?? '-' }}</span>
        </div>
        <div class="flex justify-between gap-2">
          <span class="text-gray-500">Tanggal</span>
          <span class="font-medium text-[#2E2046] text-right">
            {{ $assessment->assessment_date?->format('d M Y') ?? $assessment->created_at->format('d M Y') }}
          </span>
        </div>
        @if($assessment->total_score !== null)
          <div class="flex justify-between gap-2">
            <span class="text-gray-500">Total Skor</span>
            <span class="font-bold text-[#4A3769]">{{ $assessment->total_score }}</span>
          </div>
        @endif
      </div>
    </div>

    @if($assessment->result_description)
      <div class="dash-card">
        <h3 class="font-semibold text-[#2E2046] mb-3" style="font-family:'Playfair Display',serif">Keterangan</h3>
        <p class="text-sm text-gray-600 leading-relaxed">{{ $assessment->result_description }}</p>
      </div>
    @endif

    @if($assessment->recommendation)
      <div class="dash-card" style="border-left:3px solid {{ $color }}">
        <h3 class="font-semibold text-[#2E2046] mb-3" style="font-family:'Playfair Display',serif">Rekomendasi</h3>
        <p class="text-sm text-gray-600 leading-relaxed">{{ $assessment->recommendation }}</p>
      </div>
    @endif
  </div>
</div>

{{-- Action buttons --}}
<div class="flex flex-wrap gap-3 mt-6">
  <a href="{{ route('member.assessment.start') }}" class="btn-primary">
    <i class="fas fa-play-circle"></i> Asesmen Lagi
  </a>
  <a href="{{ route('member.assessment.history') }}" class="btn-secondary">
    <i class="fas fa-history"></i> Riwayat
  </a>
  <a href="{{ route('member.children.index') }}" class="btn-secondary">
    <i class="fas fa-child"></i> Profil Anak
  </a>
</div>

@endsection
