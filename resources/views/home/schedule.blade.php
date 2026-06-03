@extends('home.layout.master')
@section('title', 'Class Calendar — Child See')

@push('styles')
<style>
  .cal-day { min-height:88px; border:1px solid var(--border); border-radius:12px; padding:8px; background:#fff; transition:all 0.2s; cursor:pointer; }
  .cal-day:hover { border-color:var(--gold); background:var(--cream-2); }
  .cal-day.has-class { background:linear-gradient(135deg,#fdf6ec,#faf0e0); border-color:var(--gold); }
  .cal-day.today { border-color:var(--rust); box-shadow:0 0 0 2px rgba(184,92,56,.2); }
  .cal-day.other-month { opacity:0.35; }
  .cal-event { font-size:10px; font-weight:600; color:var(--brown); background:rgba(196,146,58,.15); border-radius:4px; padding:2px 5px; margin-top:3px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
</style>
@endpush

@section('content')

{{-- HERO --}}
<section class="py-16 px-6 text-center reveal">
  <div class="max-w-2xl mx-auto">
    <h1 class="text-5xl font-semibold mb-4" style="font-family:'Playfair Display',serif">Class Calendar</h1>
    <p class="text-[#7a7a7a] text-sm leading-relaxed">Click any day to see scheduled classes</p>
  </div>
</section>

<section class="px-6 pb-20">
  <div class="max-w-5xl mx-auto">

    {{-- Legend --}}
    <div class="flex items-center gap-6 mb-8 flex-wrap reveal">
      <div class="flex items-center gap-2 text-xs text-[#7a7a7a]">
        <div class="w-4 h-4 rounded-sm bg-gradient-to-br from-[#fdf6ec] to-[#faf0e0] border border-[#C4923A]"></div> Has Classes
      </div>
      <div class="flex items-center gap-2 text-xs text-[#7a7a7a]">
        <div class="w-4 h-4 rounded-sm border-2 border-[#B85C38]"></div> Today
      </div>
      <div class="flex items-center gap-2 text-xs text-[#7a7a7a]">
        <div class="w-4 h-4 rounded-sm bg-white border border-[#e8e0d0]"></div> No Classes
      </div>
    </div>

    {{-- Calendar Header --}}
    <div class="flex items-center justify-between mb-6 reveal">
      <a href="{{ route('schedule', ['month' => $prevMonth->format('Y-m')]) }}" class="w-10 h-10 rounded-full bg-[#f5f0e8] flex items-center justify-center hover:bg-[#C4923A] hover:text-white transition-all" id="cal-prev">
        <i class="fas fa-chevron-left text-sm"></i>
      </a>
      <h2 class="text-2xl font-semibold" id="cal-month" style="font-family:'Playfair Display',serif">
        {{ $currentMonth->format('F Y') }}
      </h2>
      <a href="{{ route('schedule', ['month' => $nextMonth->format('Y-m')]) }}" class="w-10 h-10 rounded-full bg-[#f5f0e8] flex items-center justify-center hover:bg-[#C4923A] hover:text-white transition-all" id="cal-next">
        <i class="fas fa-chevron-right text-sm"></i>
      </a>
    </div>

    {{-- Calendar Grid --}}
    <div class="grid grid-cols-7 gap-2 reveal" id="cal-grid">
      @foreach(['Sun','Mon','Tue','Wed','Thu','Fri','Sat'] as $dayLabel)
        <div class="text-center text-xs font-semibold text-[#7a7a7a] py-2 uppercase tracking-wider">{{ $dayLabel }}</div>
      @endforeach

      @php
        $startOfMonth = $currentMonth->copy()->startOfMonth();
        $endOfMonth   = $currentMonth->copy()->endOfMonth();
        $startDay     = $startOfMonth->dayOfWeek; // 0=Sun
        $today        = \Carbon\Carbon::today();
      @endphp

      {{-- Prev month filler --}}
      @for($i = 0; $i < $startDay; $i++)
        <div class="cal-day other-month">
          <span class="text-xs font-medium text-gray-300">{{ $startOfMonth->copy()->subDays($startDay - $i)->day }}</span>
        </div>
      @endfor

      {{-- Current month days --}}
      @for($day = 1; $day <= $endOfMonth->day; $day++)
        @php
          $date     = $currentMonth->copy()->setDay($day);
          $dateKey  = $date->toDateString();
          $events   = $calendarEvents[$dateKey] ?? collect();
          $isToday  = $date->isSameDay($today);
        @endphp
        <div class="cal-day {{ $events->count() > 0 ? 'has-class' : '' }} {{ $isToday ? 'today' : '' }}"
             onclick="openDayModal('{{ $dateKey }}')">
          <span class="text-xs font-semibold {{ $isToday ? 'text-[#B85C38]' : 'text-[#1a1a1a]' }}">{{ $day }}</span>
          @foreach($events->take(2) as $ev)
            <div class="cal-event">{{ \Carbon\Carbon::parse($ev->start_time)->format('H:i') }} {{ $ev->masterClass->name ?? '' }}</div>
          @endforeach
          @if($events->count() > 2)
            <div class="text-[9px] text-[#C4923A] mt-1">+{{ $events->count() - 2 }} more</div>
          @endif
        </div>
      @endfor

      {{-- Next month filler --}}
      @php $filled = $startDay + $endOfMonth->day; $rem = $filled % 7 === 0 ? 0 : 7 - ($filled % 7); @endphp
      @for($i = 1; $i <= $rem; $i++)
        <div class="cal-day other-month"><span class="text-xs font-medium text-gray-300">{{ $i }}</span></div>
      @endfor
    </div>

    {{-- Upcoming list --}}
    @if($upcomingClasses->count() > 0)
      <div class="mt-12 reveal">
        <h3 class="text-2xl font-semibold mb-6" style="font-family:'Playfair Display',serif">Upcoming Classes</h3>
        <div class="space-y-3">
          @foreach($upcomingClasses->take(8) as $batch)
            <div class="flex items-center gap-4 bg-white rounded-2xl p-4 border border-[#e8e0d0] card-hover">
              <div class="text-center flex-shrink-0 w-14">
                <div class="text-xs font-semibold text-[#7a7a7a] uppercase">{{ \Carbon\Carbon::parse($batch->date)->format('D') }}</div>
                <div class="text-2xl font-semibold text-[#B85C38]" style="font-family:'Playfair Display',serif">{{ \Carbon\Carbon::parse($batch->date)->format('d') }}</div>
              </div>
              <div class="flex-1">
                <h4 class="font-semibold text-sm" style="font-family:'Playfair Display',serif">{{ $batch->masterClass->name ?? 'Class' }}</h4>
                <p class="text-xs text-[#7a7a7a]">{{ \Carbon\Carbon::parse($batch->start_time)->format('H:i') }} · {{ $batch->instructor->name ?? '' }} · {{ $batch->room->room_name ?? 'Studio' }}</p>
              </div>
              <div class="text-right flex-shrink-0">
                <div class="text-xs text-[#7a7a7a] mb-1">{{ $batch->remaining_slots }} spots left</div>
                @if(!$batch->isFull())
                  <a href="{{ route('login') }}" class="btn-grad btn-grad-sm">Book Now</a>
                @else
                  <span class="text-xs text-[#9ca3af]">Full</span>
                @endif
              </div>
            </div>
          @endforeach
        </div>
      </div>
    @endif
  </div>
</section>

{{-- Day Modal --}}
<div id="day-modal" class="fixed inset-0 z-[600] bg-black/50 flex items-center justify-center p-4 opacity-0 pointer-events-none transition-opacity duration-300">
  <div class="bg-white rounded-3xl w-full max-w-md shadow-2xl">
    <div class="flex items-center justify-between p-6 border-b border-[#e8e0d0]">
      <h3 id="modal-date" class="font-semibold text-lg" style="font-family:'Playfair Display',serif">Classes</h3>
      <button onclick="closeModal()" class="w-8 h-8 rounded-full bg-[#f5f0e8] flex items-center justify-center text-[#4a4a4a] hover:bg-[#e8e0d0]">
        <i class="fas fa-times text-sm"></i>
      </button>
    </div>
    <div id="modal-events" class="p-6 max-h-96 overflow-y-auto"></div>
  </div>
</div>

@endsection

@push('scripts')
<script>
const calendarEvents = @json($calendarEventsJson ?? []);

window.openDayModal = function(dateKey) {
  const modal = document.getElementById('day-modal');
  const title = document.getElementById('modal-date');
  const list  = document.getElementById('modal-events');
  if (!modal) return;
  const d = new Date(dateKey + 'T00:00:00');
  title.textContent = d.toLocaleDateString('en-US', {weekday:'long', year:'numeric', month:'long', day:'numeric'});
  const events = calendarEvents[dateKey] || [];
  list.innerHTML = events.length
    ? events.map(e => `<div class="flex items-center gap-3 py-3 border-b border-[#e8e0d0] last:border-0">
        <div class="w-10 h-10 bg-gradient-to-br from-[#C4923A] to-[#B85C38] rounded-full flex items-center justify-center flex-shrink-0">
          <i class="fas fa-leaf text-white text-xs"></i>
        </div>
        <div class="flex-1">
          <p class="text-sm font-medium text-[#1a1a1a]">${e.name}</p>
          <p class="text-xs text-[#7a7a7a]">${e.time} · ${e.instructor} · ${e.room}</p>
          <p class="text-xs text-[#C4923A]">${e.spots} spots left · Rp ${e.price}</p>
        </div>
        ${e.available ? '<a href="{{ route('login') }}" class="btn-grad btn-grad-sm text-xs flex-shrink-0">Book</a>' : '<span class="text-xs text-[#9ca3af] flex-shrink-0">Full</span>'}
      </div>`).join('')
    : '<p class="text-sm text-[#7a7a7a] py-4 text-center">No classes scheduled for this day.</p>';
  modal.classList.remove('opacity-0', 'pointer-events-none');
  modal.classList.add('opacity-100');
};

window.closeModal = function() {
  const modal = document.getElementById('day-modal');
  if (modal) { modal.classList.add('opacity-0', 'pointer-events-none'); modal.classList.remove('opacity-100'); }
};
document.getElementById('day-modal')?.addEventListener('click', e => { if (e.target === e.currentTarget) closeModal(); });
</script>
@endpush
