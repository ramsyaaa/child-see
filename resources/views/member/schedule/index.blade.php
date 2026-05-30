@extends('member.layout.master')
@section('title', 'Class Schedule')
@section('page-title', 'Class Schedule')

@section('content')

<div class="mb-6">
  <h1 class="text-xl font-semibold text-[#1a1a1a]" style="font-family:'Playfair Display',serif">Class Schedule</h1>
  <p class="text-xs text-[#7a7a7a] mt-0.5">Find and book upcoming classes</p>
</div>

{{-- Filters --}}
<div class="dash-card mb-5">
  <form method="GET" class="flex flex-wrap gap-3 items-end">
    <div class="flex-1 min-w-[140px]">
      <label class="block text-xs font-semibold text-[#4a4a4a] mb-1.5">Date</label>
      <input type="date" name="date" class="form-input" value="{{ request('date') }}">
    </div>
    <div class="flex-1 min-w-[140px]">
      <label class="block text-xs font-semibold text-[#4a4a4a] mb-1.5">Class</label>
      <select name="master_class_id" class="form-input">
        <option value="">All Classes</option>
        @foreach($masterClasses as $mc)
          <option value="{{ $mc->id }}" {{ request('master_class_id') == $mc->id ? 'selected' : '' }}>
            {{ $mc->name }}
          </option>
        @endforeach
      </select>
    </div>
    <div class="flex-1 min-w-[140px]">
      <label class="block text-xs font-semibold text-[#4a4a4a] mb-1.5">Instructor</label>
      <select name="instructor_id" class="form-input">
        <option value="">All Instructors</option>
        @foreach($instructors as $instructor)
          <option value="{{ $instructor->id }}" {{ request('instructor_id') == $instructor->id ? 'selected' : '' }}>
            {{ $instructor->name }}
          </option>
        @endforeach
      </select>
    </div>
    <div class="flex gap-2">
      <button type="submit" class="btn-grad px-5 py-2.5 text-xs">
        <i class="fas fa-filter mr-1"></i> Filter
      </button>
      @if(request()->anyFilled(['date','master_class_id','instructor_id']))
        <a href="{{ route('member.schedule.index') }}"
           class="px-4 py-2.5 rounded-full text-xs border border-[#e8e0d0] text-[#7a7a7a] hover:text-[#B85C38] hover:border-[#B85C38] transition-colors">
          Clear
        </a>
      @endif
    </div>
  </form>
</div>

{{-- Schedule list --}}
<div class="dash-card overflow-hidden p-0">
  @if($batchClasses->count())
    <div class="divide-y divide-[#f0ebe0]">
      @foreach($batchClasses as $class)
        <div class="flex flex-col sm:flex-row sm:items-center gap-3 px-6 py-4">
          {{-- Date block --}}
          <div class="flex-shrink-0 text-center w-12">
            <div class="text-[10px] font-semibold text-[#7a7a7a] uppercase">
              {{ \Carbon\Carbon::parse($class->date)->format('D') }}
            </div>
            <div class="text-xl font-semibold text-[#B85C38]" style="font-family:'Playfair Display',serif">
              {{ \Carbon\Carbon::parse($class->date)->format('d') }}
            </div>
            <div class="text-[10px] text-[#7a7a7a]">{{ \Carbon\Carbon::parse($class->date)->format('M') }}</div>
          </div>

          {{-- Class info --}}
          <div class="flex-1 min-w-0">
            <p class="text-sm font-semibold text-[#1a1a1a]">{{ $class->masterClass->name }}</p>
            <p class="text-xs text-[#7a7a7a] mt-0.5">
              {{ date('H:i', strtotime($class->start_time)) }}–{{ date('H:i', strtotime($class->end_time)) }}
              · {{ $class->instructor->name }}
              · {{ $class->room->room_name }}
            </p>
          </div>

          {{-- Price --}}
          <div class="flex-shrink-0 text-sm font-semibold text-[#4a4a4a] hidden sm:block">
            Rp {{ number_format($class->price, 0, ',', '.') }}
          </div>

          {{-- Slots --}}
          <div class="flex-shrink-0">
            @if($class->remaining_slots > 5)
              <span class="badge-open">{{ $class->remaining_slots }} slots</span>
            @elseif($class->remaining_slots > 0)
              <span class="badge-pending">{{ $class->remaining_slots }} left</span>
            @else
              <span class="badge-full">Full</span>
            @endif
          </div>

          {{-- Book button --}}
          <div class="flex-shrink-0">
            @if($class->remaining_slots > 0)
              <button type="button"
                onclick="document.getElementById('bookModal{{ $class->id }}').classList.remove('hidden')"
                class="btn-grad text-xs px-4 py-2">
                Book
              </button>
            @else
              <span class="text-xs text-[#9ca3af]">Full</span>
            @endif
          </div>
        </div>

        {{-- Booking Modal --}}
        <div id="bookModal{{ $class->id }}"
             class="hidden fixed inset-0 z-50 flex items-center justify-center p-4"
             onclick="if(event.target===this)this.classList.add('hidden')">
          <div class="absolute inset-0 bg-black/40"></div>
          <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-md p-6">
            <div class="flex items-center justify-between mb-4">
              <h3 class="font-semibold text-[#1a1a1a]" style="font-family:'Playfair Display',serif">Book Class</h3>
              <button onclick="document.getElementById('bookModal{{ $class->id }}').classList.add('hidden')"
                      class="w-8 h-8 rounded-full bg-[#f5f0e8] flex items-center justify-center text-[#4a4a4a] hover:text-[#B85C38] transition-colors">
                <i class="fas fa-times text-xs"></i>
              </button>
            </div>

            <div class="bg-[#faf7f2] rounded-xl p-4 mb-4">
              <p class="font-semibold text-sm text-[#1a1a1a]">{{ $class->masterClass->name }}</p>
              <p class="text-xs text-[#7a7a7a] mt-1">
                {{ \Carbon\Carbon::parse($class->date)->format('d M Y') }}
                at {{ date('H:i', strtotime($class->start_time)) }}
                · {{ $class->instructor->name }}
              </p>
            </div>

            <form action="{{ route('member.bookings.store') }}" method="POST" class="space-y-4">
              @csrf
              <input type="hidden" name="batch_class_id" value="{{ $class->id }}">

              <div>
                <label class="block text-xs font-semibold text-[#4a4a4a] mb-1.5">Booking Type</label>
                <select name="booking_type" class="form-input" required
                        onchange="toggleSub(this,'{{ $class->id }}')">
                  <option value="dropin">Drop-in (Rp {{ number_format($class->price, 0, ',', '.') }})</option>
                  <option value="subscription">Use Subscription</option>
                </select>
              </div>

              <div id="subSelect{{ $class->id }}" class="hidden">
                <label class="block text-xs font-semibold text-[#4a4a4a] mb-1.5">Select Subscription</label>
                <select name="subscription_id" class="form-input">
                  <option value="">Choose...</option>
                  @foreach(auth()->user()->subscriptions()->where('status','active')->get() as $sub)
                    <option value="{{ $sub->id }}">
                      {{ $sub->product->name }} ({{ $sub->getRemainingQuota() }} remaining)
                    </option>
                  @endforeach
                </select>
              </div>

              <div class="flex gap-3 pt-2">
                <button type="button"
                        onclick="document.getElementById('bookModal{{ $class->id }}').classList.add('hidden')"
                        class="flex-1 py-2.5 rounded-full text-xs font-semibold border border-[#e8e0d0] text-[#4a4a4a] hover:border-[#C4923A] transition-colors">
                  Cancel
                </button>
                <button type="submit" class="flex-1 btn-grad text-xs py-2.5">Confirm Booking</button>
              </div>
            </form>
          </div>
        </div>
      @endforeach
    </div>

    @if($batchClasses->hasPages())
      <div class="px-6 py-4 border-t border-[#f0ebe0]">
        {{ $batchClasses->links() }}
      </div>
    @endif

  @else
    <div class="text-center py-16">
      <div class="w-14 h-14 bg-[#f5f0e8] rounded-full flex items-center justify-center mx-auto mb-3">
        <i class="fas fa-calendar-alt text-[#C4923A] text-xl"></i>
      </div>
      <p class="text-sm text-[#7a7a7a]">No classes scheduled for your filters</p>
    </div>
  @endif
</div>

@push('scripts')
<script>
function toggleSub(select, id) {
  const box = document.getElementById('subSelect' + id);
  box.classList.toggle('hidden', select.value !== 'subscription');
}
</script>
@endpush

@endsection
