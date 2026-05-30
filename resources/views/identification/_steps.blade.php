@php
$stepLabels = [1 => 'Profil Pengisi', 2 => 'Persetujuan', 3 => 'Pilih Jenis', 4 => 'Tes Identifikasi'];
@endphp
<ol class="mt-8 flex items-center gap-2 md:gap-4">
    @foreach($stepLabels as $n => $label)
    <li class="flex items-center gap-3">
        <span class="step-dot {{ $n < $currentStep ? 'done' : ($n == $currentStep ? 'active' : 'idle') }}">
            {{ $n < $currentStep ? '✓' : $n }}
        </span>
        <span class="text-sm hidden sm:inline {{ $n == $currentStep ? 'font-semibold' : '' }}"
              style="color:{{ $n == $currentStep ? '#26223A' : 'rgba(38,34,58,0.55)' }};">{{ $label }}</span>
        @if($n < count($stepLabels))
        <span class="w-6 md:w-10 h-px" style="background:#D9D9D9;"></span>
        @endif
    </li>
    @endforeach
</ol>
