@extends('home.layout.master')
@section('title', 'Classes — Child See')

@push('styles')
<style>
  .day-tab { padding: 8px 20px; border-radius: 999px; font-size: 13px; font-weight: 500; color: #4a4a4a; background: #fff; border: 1px solid #e8e0d0; cursor: pointer; transition: all 0.2s; }
  .day-tab.active, .day-tab:hover { background: #c4923a; color: white; border-color: #c4923a; }
  .class-card { border: 1px solid #e8e0d0; background: white; border-radius: 12px; overflow: hidden; }
</style>
@endpush

@section('content')

{{-- HERO --}}
<section class="py-16 px-6 text-center reveal">
  <div class="max-w-2xl mx-auto">
    <h1 class="text-5xl font-semibold mb-4" style="font-family:'Playfair Display',serif">Weekly Class Schedule</h1>
    <p class="text-[#7a7a7a] text-sm leading-relaxed">Browse our full schedule and book your next session</p>
  </div>
</section>

{{-- WEEK NAVIGATION --}}
<section class="px-6 pb-4">
  <div class="max-w-6xl mx-auto">
    <div class="flex items-center justify-between mb-6 reveal">
      <a href="{{ route('classes', ['week' => ($weekOffset ?? 0) - 1]) }}" class="flex items-center gap-2 text-sm font-medium text-[#4a4a4a] hover:text-[#B85C38] transition-colors">
        <i class="fas fa-chevron-left text-xs"></i> Previous Week
      </a>
      <h3 class="text-sm font-semibold text-[#1a1a1a]">
        {{ $weekStart->format('M j') }} – {{ $weekEnd->format('M j, Y') }}
      </h3>
      <a href="{{ route('classes', ['week' => ($weekOffset ?? 0) + 1]) }}" class="flex items-center gap-2 text-sm font-medium text-[#4a4a4a] hover:text-[#B85C38] transition-colors">
        Next Week <i class="fas fa-chevron-right text-xs"></i>
      </a>
    </div>

    {{-- Day tabs --}}
    <div class="flex gap-2 flex-wrap mb-8 reveal" id="day-tabs">
      @foreach($classesByDay as $dayName => $dayClasses)
        <button class="day-tab {{ $loop->first ? 'active' : '' }}" onclick="showDay('{{ $dayName }}', this)">
          {{ $dayName }}
          @if($dayClasses->count() > 0)
            <span class="ml-1 text-[10px]">({{ $dayClasses->count() }})</span>
          @endif
        </button>
      @endforeach
    </div>

    {{-- Classes by day --}}
    @foreach($classesByDay as $dayName => $dayClasses)
      <div class="day-section {{ $loop->first ? '' : 'hidden' }}" data-day="{{ $dayName }}">
        @if($dayClasses->count() > 0)
          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($dayClasses as $batch)
              <div class="class-card card-hover reveal">
                <div class="p-5">
                  <div class="flex items-center justify-between mb-3">
                    <div class="flex items-center gap-2">
                      <span class="pill">{{ $batch->masterClass->category ?? 'Wellness' }}</span>
                    </div>
                    @if($batch->isFull())
                      <span class="badge-full">Full</span>
                    @else
                      <span class="badge-live">Open</span>
                    @endif
                  </div>
                  <h3 class="text-lg font-semibold mb-1" style="font-family:'Playfair Display',serif">{{ $batch->masterClass->name ?? 'Class' }}</h3>
                  <p class="text-xs text-[#7a7a7a] mb-3">with {{ $batch->instructor->name ?? 'Instructor' }}</p>
                  <div class="grid grid-cols-2 gap-2 text-xs text-[#7a7a7a] mb-4">
                    <span><i class="fas fa-clock text-[#C4923A] mr-1"></i>
                      {{ \Carbon\Carbon::parse($batch->start_time)->format('H:i') }} – {{ \Carbon\Carbon::parse($batch->end_time)->format('H:i') }}
                    </span>
                    <span><i class="fas fa-map-marker-alt text-[#C4923A] mr-1"></i>{{ $batch->room->room_name ?? 'Studio' }}</span>
                    <span><i class="fas fa-users text-[#C4923A] mr-1"></i>{{ $batch->remaining_slots }}/{{ $batch->capacity }} spots</span>
                    <span><i class="fas fa-tag text-[#C4923A] mr-1"></i>Rp {{ number_format($batch->price, 0, ',', '.') }}</span>
                  </div>
                  @if(!$batch->isFull())
                    <a href="{{ route('login') }}" class="btn-grad btn-grad-sm w-full text-center block">Book Now</a>
                  @else
                    <button class="w-full py-2.5 rounded-full text-sm font-semibold bg-[#f5f0e8] text-[#9ca3af] cursor-not-allowed">Class Full</button>
                  @endif
                </div>
              </div>
            @endforeach
          </div>
        @else
          <div class="text-center py-16 reveal">
            <i class="fas fa-calendar-times text-[#e8e0d0] text-5xl mb-4"></i>
            <p class="text-[#7a7a7a]">No classes scheduled for {{ $dayName }}.</p>
            <a href="{{ route('schedule') }}" class="inline-block mt-4 text-[#B85C38] text-sm hover:underline">View the full calendar</a>
          </div>
        @endif
      </div>
    @endforeach
  </div>
</section>

{{-- CTA --}}
<section class="py-16 px-6 bg-gradient-to-br from-[#8B4513] to-[#C4923A] text-white text-center mt-8">
  <div class="max-w-2xl mx-auto reveal">
    <h2 class="text-3xl font-semibold mb-3" style="font-family:'Playfair Display',serif">Ready to Book?</h2>
    <p class="text-white/70 text-sm mb-8 leading-relaxed">Create an account or sign in to book a class and start your wellness journey.</p>
    <div class="flex flex-wrap gap-3 justify-center">
      <a href="{{ route('register') }}" class="inline-block bg-white text-[#8B4513] px-8 py-3 rounded-full text-sm font-semibold hover:bg-[#f5f0e8] transition-colors">Create Account</a>
      <a href="{{ route('login') }}" class="inline-block bg-white/20 text-white px-8 py-3 rounded-full text-sm font-semibold hover:bg-white/30 transition-colors">Sign In</a>
    </div>
  </div>
</section>

@endsection

@push('scripts')
<script>
function showDay(day, btn) {
  document.querySelectorAll('.day-section').forEach(s => s.classList.add('hidden'));
  document.querySelectorAll('.day-tab').forEach(b => b.classList.remove('active'));
  document.querySelector('[data-day="'+day+'"]').classList.remove('hidden');
  btn.classList.add('active');
}
</script>
@endpush
