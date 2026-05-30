@extends('member.layout.master')
@section('title', 'Riwayat Asesmen')
@section('page-title', 'Riwayat Asesmen')

@section('content')

{{-- Header --}}
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
  <div>
    <h1 class="text-2xl font-semibold text-[#2E2046]" style="font-family:'Playfair Display',serif">Riwayat Asesmen</h1>
    <p class="text-sm text-gray-500 mt-1">Semua asesmen yang telah diselesaikan</p>
  </div>
  <a href="{{ route('member.assessment.start') }}" class="btn-primary flex-shrink-0">
    <i class="fas fa-plus"></i> Asesmen Baru
  </a>
</div>

{{-- Filter --}}
<form method="GET" action="{{ route('member.assessment.history') }}" class="dash-card mb-6 flex flex-wrap items-center gap-3">
  <div class="flex-1 min-w-40">
    <select name="category_id" class="form-input text-sm">
      <option value="">Semua Kategori</option>
      @foreach($categories as $category)
        <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
          {{ $category->name }}
        </option>
      @endforeach
    </select>
  </div>
  <button type="submit" class="btn-primary text-sm py-2">
    <i class="fas fa-filter"></i> Filter
  </button>
  @if(request('category_id'))
    <a href="{{ route('member.assessment.history') }}" class="btn-secondary text-sm py-2">
      <i class="fas fa-times"></i> Reset
    </a>
  @endif
</form>

{{-- Table --}}
<div class="dash-card">
  @if($assessments->isEmpty())
    <div class="text-center py-16">
      <div class="w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-4" style="background:#F0EEF5">
        <i class="fas fa-clipboard-list text-[#BAA6D6] text-3xl"></i>
      </div>
      <h3 class="font-semibold text-[#2E2046] mb-2" style="font-family:'Playfair Display',serif">Belum Ada Asesmen</h3>
      <p class="text-sm text-gray-500 mb-5">Mulai lakukan asesmen untuk melihat riwayat di sini</p>
      <a href="{{ route('member.assessment.start') }}" class="btn-primary">
        <i class="fas fa-play-circle"></i> Mulai Asesmen
      </a>
    </div>
  @else
    <div class="overflow-x-auto">
      <table class="w-full text-sm">
        <thead>
          <tr style="border-bottom:2px solid rgba(186,166,214,.2)">
            <th class="text-left pb-3 pr-4 text-xs font-semibold uppercase tracking-wider text-gray-500">Kode</th>
            <th class="text-left pb-3 pr-4 text-xs font-semibold uppercase tracking-wider text-gray-500">Anak</th>
            <th class="text-left pb-3 pr-4 text-xs font-semibold uppercase tracking-wider text-gray-500">Kategori</th>
            <th class="text-center pb-3 pr-4 text-xs font-semibold uppercase tracking-wider text-gray-500">Hasil</th>
            <th class="text-center pb-3 pr-4 text-xs font-semibold uppercase tracking-wider text-gray-500">Skor</th>
            <th class="text-left pb-3 pr-4 text-xs font-semibold uppercase tracking-wider text-gray-500">Tanggal</th>
            <th class="pb-3"></th>
          </tr>
        </thead>
        <tbody>
          @foreach($assessments as $assessment)
            <tr class="border-b hover:bg-[#F5F5F6] transition-colors" style="border-color:rgba(186,166,214,.1)">
              <td class="py-3 pr-4">
                <span class="font-mono text-xs font-semibold text-[#4A3769]">
                  {{ $assessment->assessment_code }}
                </span>
              </td>
              <td class="py-3 pr-4">
                <span class="font-medium text-[#2E2046]">{{ $assessment->child->full_name ?? '-' }}</span>
              </td>
              <td class="py-3 pr-4 text-gray-600">{{ $assessment->category->name ?? '-' }}</td>
              <td class="py-3 pr-4 text-center">
                @php
                  $sc = ['normal'=>'#839986','light'=>'#8D77AB','medium'=>'#A86916','heavy'=>'#dc3545'];
                  $rc = $sc[$assessment->severity_level] ?? '#6b7280';
                @endphp
                <span class="text-xs px-2 py-0.5 rounded-full font-semibold text-white" style="background:{{ $rc }}">
                  {{ $assessment->result_label ?? '-' }}
                </span>
              </td>
              <td class="py-3 pr-4 text-center font-semibold text-[#4A3769]">
                {{ $assessment->total_score ?? '-' }}
              </td>
              <td class="py-3 pr-4 text-gray-500 text-xs whitespace-nowrap">
                {{ \Carbon\Carbon::parse($assessment->created_at)->format('d M Y') }}
              </td>
              <td class="py-3">
                <a href="{{ route('member.assessment.result', $assessment->id) }}"
                   class="inline-flex items-center gap-1 text-xs font-medium text-[#4A3769] hover:text-[#5C477F] transition-colors">
                  Detail <i class="fas fa-arrow-right text-[10px]"></i>
                </a>
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>

    {{-- Pagination --}}
    @if($assessments->hasPages())
      <div class="mt-5 flex justify-center">
        {{ $assessments->appends(request()->query())->links() }}
      </div>
    @endif
  @endif
</div>

@endsection