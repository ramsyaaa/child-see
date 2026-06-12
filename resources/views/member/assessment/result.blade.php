@extends('member.layout.master')
@section('title', 'Hasil Asesmen')
@section('page-title', 'Hasil Asesmen')

@section('content')

@php
  $severityColors = ['normal'=>'#839986','light'=>'#8D77AB','medium'=>'#A86916','heavy'=>'#dc3545'];
  $color = $severityColors[$assessment->severity_level] ?? ($assessment->color ?? '#4A3769');
  $groupedAnswers = $assessment->answers->groupBy(fn($a) => $a->questionnaire?->domain?->name ?? 'Umum');
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
        <span><i class="fas fa-chart-bar mr-2"></i>Skor Total: {{ $assessment->total_score }}</span>
      @endif
    </div>
  </div>
  <div class="absolute right-6 top-1/2 -translate-y-1/2 opacity-10 text-[100px] leading-none pointer-events-none">
    <i class="fas fa-clipboard-check"></i>
  </div>
</div>

{{-- Action buttons --}}
<div class="flex flex-wrap gap-2 mb-6">
  <a href="{{ route('member.assessment.pdf', $assessment) }}"
     class="inline-flex items-center gap-2 text-sm font-semibold px-4 py-2 rounded-xl transition-all"
     style="background:#dc3545;color:#fff" target="_blank">
    <i class="fas fa-file-pdf"></i> Export PDF
  </a>
  <a href="{{ route('member.assessment.start') }}" class="btn-primary text-sm px-4 py-2">
    <i class="fas fa-play-circle"></i> Asesmen Lagi
  </a>
  <a href="{{ route('member.assessment.history') }}" class="btn-secondary text-sm px-4 py-2">
    <i class="fas fa-history"></i> Riwayat
  </a>
  <a href="{{ route('member.children.index') }}" class="btn-secondary text-sm px-4 py-2">
    <i class="fas fa-child"></i> Profil Anak
  </a>
</div>

{{-- Result illustration --}}
@if(!empty($assessment->category->result_illustration))
<div class="mb-6 rounded-2xl overflow-hidden shadow-sm" style="max-height:220px">
  <img src="{{ asset($assessment->category->result_illustration) }}"
       alt="{{ $assessment->category->name }}"
       class="w-full object-cover object-center" style="max-height:220px;width:100%">
</div>
@endif

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">

  {{-- Domain scores --}}
  <div class="lg:col-span-2 dash-card">
    <h3 class="font-semibold text-[#2E2046] mb-5" style="font-family:'Playfair Display',serif">Skor per Domain</h3>

    @if($assessment->domainScores && $assessment->domainScores->count())
      <div class="space-y-3">
        @foreach($assessment->domainScores as $ds)
          @php
            $dsColor = $severityColors[$ds->severity_level ?? ''] ?? '#6b7280';
            $maxPossible = $ds->domain?->questionnaires()->where('is_active',true)->count() * 2;
            $pct = $maxPossible > 0 ? min(100, round(($ds->total_score / $maxPossible) * 100)) : 0;
          @endphp
          <div class="p-4 rounded-xl" style="background:#F7F4FC;border:1px solid rgba(186,166,214,.15)">
            <div class="flex items-center justify-between mb-2">
              <span class="text-sm font-semibold text-[#2E2046]">{{ $ds->domain->name ?? '-' }}</span>
              <div class="flex items-center gap-2">
                <span class="text-sm font-bold text-[#4A3769]">{{ $ds->total_score ?? 0 }}</span>
                <span class="text-xs px-2 py-0.5 rounded-full font-semibold text-white" style="background:{{ $dsColor }}">
                  {{ $ds->result_label ?? '-' }}
                </span>
              </div>
            </div>
            <div class="w-full rounded-full h-2" style="background:rgba(186,166,214,.25)">
              <div class="h-2 rounded-full" style="width:{{ $pct }}%;background:{{ $dsColor }}"></div>
            </div>
            @if($ds->result_description)
              <p class="text-xs text-gray-500 mt-2 leading-relaxed">{{ $ds->result_description }}</p>
            @endif
          </div>
        @endforeach
      </div>
    @else
      <p class="text-sm text-gray-500">Data skor domain tidak tersedia.</p>
    @endif
  </div>

  {{-- Info & recommendation --}}
  <div class="space-y-4">
    <div class="dash-card">
      <h3 class="font-semibold text-[#2E2046] mb-4" style="font-family:'Playfair Display',serif">Informasi</h3>
      @if($assessment->child->photo_url)
        <div class="flex justify-center mb-4">
          <img src="{{ $assessment->child->photo_url }}" alt="{{ $assessment->child->full_name }}"
               class="w-16 h-16 rounded-xl object-cover" style="border:2px solid rgba(186,166,214,.3)">
        </div>
      @endif
      <div class="space-y-3 text-sm">
        <div class="flex justify-between gap-2">
          <span class="text-gray-500">Anak</span>
          <span class="font-medium text-[#2E2046] text-right">{{ $assessment->child->full_name ?? '-' }}</span>
        </div>
        @if($assessment->child->birth_date)
        <div class="flex justify-between gap-2">
          <span class="text-gray-500">Usia</span>
          <span class="font-medium text-[#2E2046]">{{ \Carbon\Carbon::parse($assessment->child->birth_date)->age }} tahun</span>
        </div>
        @endif
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
          <div class="flex justify-between gap-2 pt-2" style="border-top:1px solid rgba(186,166,214,.2)">
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

{{-- Answers detail --}}
@if($groupedAnswers->isNotEmpty())
<div class="dash-card">
  <div class="flex items-center justify-between mb-5">
    <h3 class="font-semibold text-[#2E2046]" style="font-family:'Playfair Display',serif">Rincian Jawaban</h3>
    <span class="text-xs text-gray-400">{{ $assessment->answers->count() }} pertanyaan</span>
  </div>

  @if($groupedAnswers->count() > 1)
  <div class="flex flex-wrap gap-2 mb-4">
    @foreach($groupedAnswers->keys() as $i => $domainName)
      <button onclick="showDomain({{ $i }})" id="tab-{{ $i }}"
              class="answer-tab text-xs font-semibold px-3 py-1.5 rounded-lg transition-all"
              style="{{ $i===0 ? 'background:#4A3769;color:#fff' : 'background:#F0EEF5;color:#4A3769' }}">
        {{ $domainName }}
      </button>
    @endforeach
  </div>
  @endif

  @foreach($groupedAnswers as $domainName => $answers)
    @php $di = $groupedAnswers->keys()->search($domainName); @endphp
    <div class="domain-answers" id="domain-pane-{{ $di }}" style="{{ $di > 0 ? 'display:none' : '' }}">
      <div class="space-y-2">
        @foreach($answers as $qIdx => $answer)
          @php
            $score = $answer->score ?? 0;
            $scoreColor = $score == 0 ? '#839986' : ($score == 1 ? '#A86916' : '#dc3545');
          @endphp
          <div class="flex gap-3 p-3 rounded-xl" style="background:#F7F4FC;border:1px solid rgba(186,166,214,.12)">
            <div class="flex-shrink-0 w-6 h-6 rounded-full flex items-center justify-center text-xs font-bold text-white mt-0.5"
                 style="background:#BAA6D6;min-width:1.5rem">{{ $qIdx+1 }}</div>
            <div class="flex-1 min-w-0">
              <p class="text-sm text-[#2E2046] leading-relaxed mb-1.5">{{ $answer->questionnaire?->question ?? '-' }}</p>
              <div class="flex items-center gap-2">
                <span class="text-xs font-semibold px-2.5 py-0.5 rounded-full text-white"
                      style="background:{{ $scoreColor }}">
                  {{ $answer->answerOption?->label ?? '-' }}
                </span>
                <span class="text-xs text-gray-400">Skor: <strong style="color:{{ $scoreColor }}">{{ $score }}</strong></span>
              </div>
            </div>
          </div>
        @endforeach
      </div>
    </div>
  @endforeach
</div>
@endif

@push('scripts')
<script>
function showDomain(idx) {
  document.querySelectorAll('.domain-answers').forEach(function(el, i) {
    el.style.display = i === idx ? '' : 'none';
  });
  document.querySelectorAll('.answer-tab').forEach(function(btn, i) {
    btn.style.background = i === idx ? '#4A3769' : '#F0EEF5';
    btn.style.color = i === idx ? '#fff' : '#4A3769';
  });
}
</script>
@endpush

@endsection
