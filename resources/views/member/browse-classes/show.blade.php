@extends('member.layout.master')
@section('title', $masterClass->name)
@section('page-title', 'Class Details')

@section('content')

<div class="flex items-center gap-2 mb-6">
  <a href="{{ route('member.browse-classes.index') }}"
     class="w-8 h-8 rounded-full bg-[#f5f0e8] flex items-center justify-center text-[#4a4a4a] hover:text-[#B85C38] transition-colors">
    <i class="fas fa-arrow-left text-xs"></i>
  </a>
  <div>
    <h1 class="text-xl font-semibold text-[#1a1a1a]" style="font-family:'Playfair Display',serif">{{ $masterClass->name }}</h1>
  </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

  {{-- Main --}}
  <div class="lg:col-span-2 space-y-5">

    {{-- Class info --}}
    <div class="dash-card">
      <div class="h-1 rounded-full mb-5" style="background:{{ $masterClass->color ?? '#C4923A' }}"></div>

      <div class="flex flex-wrap gap-2 mb-4">
        <span class="text-[10px] px-2.5 py-1 rounded-full font-semibold
          {{ $masterClass->difficulty_level == 'beginner' ? 'bg-green-100 text-green-700'
             : ($masterClass->difficulty_level == 'intermediate' ? 'bg-amber-100 text-amber-700'
             : 'bg-red-100 text-red-700') }}">
          {{ ucfirst($masterClass->difficulty_level) }}
        </span>
        <span class="text-[10px] px-2.5 py-1 rounded-full font-semibold bg-[#f5f0e8] text-[#7a7a7a]">
          {{ $masterClass->category }}
        </span>
        <span class="text-[10px] px-2.5 py-1 rounded-full font-semibold bg-[#f5f0e8] text-[#7a7a7a]">
          <i class="fas fa-clock mr-1"></i>{{ $masterClass->default_duration }} min
        </span>
      </div>

      @if($masterClass->description)
        <p class="text-sm text-[#4a4a4a] leading-relaxed">{{ $masterClass->description }}</p>
      @endif
    </div>

    {{-- Upcoming sessions --}}
    <div class="dash-card overflow-hidden p-0">
      <div class="px-6 py-4 border-b border-[#f0ebe0]">
        <h3 class="font-semibold text-[#1a1a1a] text-sm" style="font-family:'Playfair Display',serif">Upcoming Sessions</h3>
      </div>
      @if($upcomingClasses->count())
        <div class="divide-y divide-[#f0ebe0]">
          @foreach($upcomingClasses as $class)
            <div class="flex flex-col sm:flex-row sm:items-center gap-3 px-6 py-4">
              <div class="flex-shrink-0 text-center w-12">
                <div class="text-[10px] font-semibold text-[#7a7a7a] uppercase">{{ $class->date->format('D') }}</div>
                <div class="text-xl font-semibold text-[#B85C38]" style="font-family:'Playfair Display',serif">{{ $class->date->format('d') }}</div>
                <div class="text-[10px] text-[#7a7a7a]">{{ $class->date->format('M') }}</div>
              </div>
              <div class="flex-1 min-w-0">
                <p class="text-sm font-semibold text-[#1a1a1a]">
                  {{ date('H:i', strtotime($class->start_time)) }}–{{ date('H:i', strtotime($class->end_time)) }}
                </p>
                <p class="text-xs text-[#7a7a7a] mt-0.5">
                  {{ $class->instructor->name }} · {{ $class->room->room_name }}
                </p>
              </div>
              <div class="text-sm font-semibold text-[#4a4a4a] hidden sm:block">
                Rp {{ number_format($class->price, 0, ',', '.') }}
              </div>
              <div>
                @if($class->remaining_slots > 5)
                  <span class="badge-open">{{ $class->remaining_slots }} slots</span>
                @elseif($class->remaining_slots > 0)
                  <span class="badge-pending">{{ $class->remaining_slots }} left</span>
                @else
                  <span class="badge-full">Full</span>
                @endif
              </div>
              <div>
                @if($class->remaining_slots > 0)
                  <a href="{{ route('member.schedule.index') }}"
                     class="text-xs text-[#C4923A] hover:text-[#B85C38] border border-[#e8d5b5] hover:border-[#C4923A] px-3 py-1.5 rounded-full transition-colors font-medium">
                    Book
                  </a>
                @endif
              </div>
            </div>
          @endforeach
        </div>
      @else
        <div class="text-center py-10">
          <p class="text-xs text-[#7a7a7a]">No upcoming sessions scheduled yet</p>
        </div>
      @endif
    </div>

  </div>

  {{-- Sidebar --}}
  <div>
    <div class="dash-card">
      <h3 class="font-semibold text-[#1a1a1a] mb-4 text-sm" style="font-family:'Playfair Display',serif">Quick Actions</h3>
      <div class="space-y-2">
        <a href="{{ route('member.schedule.index') }}"
           class="flex items-center gap-3 p-3 rounded-xl bg-[#fdf6ec] hover:bg-[#f5e9d5] transition-colors">
          <div class="w-8 h-8 rounded-lg bg-[#C4923A] flex items-center justify-center">
            <i class="fas fa-calendar text-white text-xs"></i>
          </div>
          <span class="text-sm font-medium text-[#4a4a4a]">Full Schedule</span>
          <i class="fas fa-chevron-right text-[#C4923A] text-xs ml-auto"></i>
        </a>
        <a href="{{ route('member.products.index') }}"
           class="flex items-center gap-3 p-3 rounded-xl bg-[#fdf6ec] hover:bg-[#f5e9d5] transition-colors">
          <div class="w-8 h-8 rounded-lg bg-[#B85C38] flex items-center justify-center">
            <i class="fas fa-ticket-alt text-white text-xs"></i>
          </div>
          <span class="text-sm font-medium text-[#4a4a4a]">Buy Package</span>
          <i class="fas fa-chevron-right text-[#C4923A] text-xs ml-auto"></i>
        </a>
      </div>
    </div>
  </div>

</div>

@endsection
