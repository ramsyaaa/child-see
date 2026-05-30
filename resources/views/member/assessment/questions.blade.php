@extends('member.layout.master')
@section('title', 'Asesmen — ' . ($assessment->category->name ?? 'Pertanyaan'))
@section('page-title', 'Asesmen: ' . ($assessment->category->name ?? ''))

@section('content')

{{-- Progress header --}}
<div class="dash-card mb-6">
  <div class="flex flex-col sm:flex-row sm:items-center gap-4 justify-between">
    <div>
      <h2 class="text-lg font-semibold text-[#2E2046]" style="font-family:'Playfair Display',serif">
        {{ $assessment->category->name ?? 'Asesmen' }}
      </h2>
      <p class="text-sm text-gray-500 mt-1">
        Anak: <span class="font-semibold text-[#4A3769]">{{ $assessment->child->full_name ?? '-' }}</span>
      </p>
    </div>
    <div class="text-xs text-gray-500 text-right">
      <span class="font-mono font-semibold text-[#4A3769]">{{ $assessment->assessment_code }}</span>
    </div>
  </div>

  {{-- Domain progress --}}
  @php $domainKeys = array_keys($grouped->toArray()); $totalDomains = count($domainKeys); @endphp
  @if($totalDomains > 1)
    <div class="mt-4 flex flex-wrap gap-2">
      @foreach($domainKeys as $i => $domainName)
        <a href="#domain-{{ $i+1 }}" class="text-xs px-3 py-1 rounded-full transition-all" style="background:#F0EEF5;color:#4A3769;border:1px solid rgba(186,166,214,.3)">
          {{ $i+1 }}. {{ $domainName }}
        </a>
      @endforeach
    </div>
  @endif
</div>

<form action="{{ route('member.assessment.submit', $assessment) }}" method="POST" id="assessmentForm">
  @csrf
  <input type="hidden" name="assessment_id" value="{{ $assessment->id }}">

  @foreach($grouped as $domainName => $questionnaires)
    @php $domainIndex = array_search($domainName, $domainKeys) + 1; @endphp
    <div class="dash-card mb-6" id="domain-{{ $domainIndex }}">
      {{-- Domain header --}}
      <div class="flex items-center gap-3 mb-5 pb-4 border-b" style="border-color:rgba(186,166,214,.2)">
        <div class="w-8 h-8 rounded-full flex items-center justify-center text-white text-xs font-bold flex-shrink-0" style="background:#4A3769">
          {{ $domainIndex }}
        </div>
        <div>
          <h3 class="font-semibold text-[#2E2046]" style="font-family:'Playfair Display',serif">{{ $domainName }}</h3>
          <p class="text-xs text-gray-500">Domain {{ $domainIndex }} dari {{ $totalDomains }}</p>
        </div>
      </div>

      {{-- Questions --}}
      <div class="space-y-5">
        @foreach($questionnaires as $qIndex => $questionnaire)
          <div class="p-4 rounded-xl" style="background:#F5F5F6;border:1px solid rgba(186,166,214,.15)">
            <p class="text-sm font-medium text-[#2E2046] mb-4 leading-relaxed">
              <span class="text-[#BAA6D6] font-bold mr-2">{{ $qIndex + 1 }}.</span>
              {{ $questionnaire->question }}
            </p>

            <div class="flex flex-col sm:flex-row gap-2 sm:gap-4">
              @foreach($questionnaire->answerOptions as $option)
                <label class="answer-option cursor-pointer flex items-center gap-3 flex-1 p-3 rounded-xl border-2 transition-all" style="border-color:rgba(186,166,214,.3);background:#fff">
                  <input type="radio" name="answers[{{ $questionnaire->id }}]" value="{{ $option->id }}"
                         class="sr-only answer-radio" required>
                  <div class="w-4 h-4 rounded-full border-2 flex items-center justify-center flex-shrink-0 radio-indicator" style="border-color:#BAA6D6">
                    <div class="w-2 h-2 rounded-full radio-dot hidden" style="background:#4A3769"></div>
                  </div>
                  <span class="text-sm text-gray-700">{{ $option->label }}</span>
                </label>
              @endforeach
            </div>
          </div>
        @endforeach
      </div>
    </div>
  @endforeach

  {{-- Submit --}}
  <div class="flex items-center justify-between">
    <a href="{{ route('member.assessment.start') }}" class="btn-secondary">
      <i class="fas fa-arrow-left"></i> Batalkan
    </a>
    <button type="submit" class="btn-primary text-base px-8 py-3">
      <i class="fas fa-check"></i> Selesai & Lihat Hasil
    </button>
  </div>
</form>

@push('styles')
<style>
  .answer-selected { border-color: #4A3769 !important; background: #F0EEF5 !important; }
  .answer-selected .radio-indicator { border-color: #4A3769 !important; }
  .answer-selected .radio-dot { display: block !important; }
</style>
@endpush

@push('scripts')
<script>
  document.querySelectorAll('.answer-radio').forEach(function(radio) {
    radio.addEventListener('change', function() {
      var siblings = document.querySelectorAll('input[name="' + this.name + '"]');
      siblings.forEach(function(sib) {
        sib.closest('.answer-option').classList.remove('answer-selected');
      });
      if (this.checked) {
        this.closest('.answer-option').classList.add('answer-selected');
      }
    });
  });

  document.getElementById('assessmentForm').addEventListener('submit', function(e) {
    var radios = document.querySelectorAll('.answer-radio');
    var names = new Set();
    radios.forEach(function(r) { names.add(r.name); });
    var answered = 0;
    names.forEach(function(name) {
      var checked = document.querySelector('input[name="' + name + '"]:checked');
      if (checked) answered++;
    });
    if (answered < names.size) {
      e.preventDefault();
      alert('Harap jawab semua pertanyaan sebelum melanjutkan.');
    }
  });
</script>
@endpush

@endsection