@extends('member.layout.master')
@section('title', 'Subscription Details')
@section('page-title', 'Subscription Details')

@section('content')

<div class="flex items-center gap-2 mb-6">
  <a href="{{ route('member.subscriptions.index') }}"
     class="w-8 h-8 rounded-full bg-[#f5f0e8] flex items-center justify-center text-[#4a4a4a] hover:text-[#B85C38] transition-colors">
    <i class="fas fa-arrow-left text-xs"></i>
  </a>
  <div>
    <h1 class="text-xl font-semibold text-[#1a1a1a]" style="font-family:'Playfair Display',serif">Subscription Details</h1>
  </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

  {{-- Main info --}}
  <div class="lg:col-span-2 space-y-5">

    {{-- Header card --}}
    <div class="dash-card">
      <div class="flex items-start justify-between mb-4">
        <div>
          <h2 class="font-semibold text-[#1a1a1a] text-base" style="font-family:'Playfair Display',serif">
            {{ $subscription->product->name }}
          </h2>
          @if($subscription->product->description)
            <p class="text-xs text-[#7a7a7a] mt-1 leading-relaxed">{{ $subscription->product->description }}</p>
          @endif
        </div>
        @if($subscription->status == 'active')
          <span class="badge-active">Active</span>
        @elseif($subscription->status == 'expired')
          <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-gray-100 text-gray-500">Expired</span>
        @else
          <span class="badge-full">Cancelled</span>
        @endif
      </div>

      <div class="grid grid-cols-2 gap-4 text-sm">
        <div>
          <p class="text-[10px] font-semibold text-[#7a7a7a] uppercase tracking-wider mb-1">Start Date</p>
          <p class="font-medium text-[#1a1a1a]">{{ $subscription->start_date->format('d M Y') }}</p>
        </div>
        <div>
          <p class="text-[10px] font-semibold text-[#7a7a7a] uppercase tracking-wider mb-1">End Date</p>
          <p class="font-medium text-[#1a1a1a]">{{ $subscription->end_date->format('d M Y') }}</p>
        </div>
        <div>
          <p class="text-[10px] font-semibold text-[#7a7a7a] uppercase tracking-wider mb-1">Quota Allocated</p>
          <p class="font-medium text-[#1a1a1a]">{{ $subscription->quota_allocated ?? 'Unlimited' }}</p>
        </div>
        <div>
          <p class="text-[10px] font-semibold text-[#7a7a7a] uppercase tracking-wider mb-1">Remaining</p>
          <p class="font-semibold text-[#C4923A]">{{ $subscription->getRemainingQuota() }}</p>
        </div>
      </div>

      @if($subscription->quota_allocated)
        @php $pct = min(100, round(($subscription->quota_used / $subscription->quota_allocated) * 100)); @endphp
        <div class="mt-4">
          <div class="flex justify-between text-xs text-[#7a7a7a] mb-1.5">
            <span>{{ $subscription->quota_used }} used</span>
            <span>{{ $pct }}%</span>
          </div>
          <div class="h-2 rounded-full bg-[#f0ebe0] overflow-hidden">
            <div class="h-full rounded-full" style="width:{{ $pct }}%; background:linear-gradient(90deg,#C4923A,#B85C38)"></div>
          </div>
        </div>
      @endif
    </div>

    {{-- Booking history --}}
    <div class="dash-card overflow-hidden p-0">
      <div class="px-6 py-4 border-b border-[#f0ebe0]">
        <h3 class="font-semibold text-[#1a1a1a] text-sm" style="font-family:'Playfair Display',serif">Booking History</h3>
      </div>
      @if($bookings->count())
        <div class="divide-y divide-[#f0ebe0]">
          @foreach($bookings as $booking)
            <div class="flex items-center gap-4 px-6 py-3">
              <div class="flex-shrink-0 text-center w-10">
                <div class="text-[10px] font-semibold text-[#7a7a7a] uppercase">{{ $booking->batchClass->date->format('D') }}</div>
                <div class="text-base font-semibold text-[#B85C38]" style="font-family:'Playfair Display',serif">{{ $booking->batchClass->date->format('d') }}</div>
              </div>
              <div class="flex-1 min-w-0">
                <p class="text-sm font-semibold text-[#1a1a1a] truncate">{{ $booking->batchClass->masterClass->name }}</p>
                <p class="text-xs text-[#7a7a7a]">{{ $booking->batchClass->instructor->name }}</p>
              </div>
              @if($booking->status == 'booked')
                <span class="badge-open">Booked</span>
              @elseif($booking->status == 'completed')
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-gray-100 text-gray-500">Done</span>
              @else
                <span class="badge-full">{{ ucfirst($booking->status) }}</span>
              @endif
            </div>
          @endforeach
        </div>
      @else
        <div class="text-center py-10">
          <p class="text-xs text-[#7a7a7a]">No bookings yet with this subscription</p>
        </div>
      @endif
    </div>

  </div>

  {{-- Sidebar --}}
  <div class="space-y-4">
    <div class="dash-card">
      <h3 class="font-semibold text-[#1a1a1a] mb-4 text-sm" style="font-family:'Playfair Display',serif">Quick Actions</h3>
      <div class="space-y-2">
        @if($subscription->status == 'active' && $subscription->hasQuotaRemaining())
          <a href="{{ route('member.schedule.index') }}"
             class="flex items-center gap-3 p-3 rounded-xl bg-[#fdf6ec] hover:bg-[#f5e9d5] transition-colors">
            <div class="w-8 h-8 rounded-lg bg-[#C4923A] flex items-center justify-center">
              <i class="fas fa-calendar-check text-white text-xs"></i>
            </div>
            <span class="text-sm font-medium text-[#4a4a4a]">Book a Class</span>
            <i class="fas fa-chevron-right text-[#C4923A] text-xs ml-auto"></i>
          </a>
        @endif
        <a href="{{ route('member.bookings.index') }}"
           class="flex items-center gap-3 p-3 rounded-xl bg-[#fdf6ec] hover:bg-[#f5e9d5] transition-colors">
          <div class="w-8 h-8 rounded-lg bg-[#B85C38] flex items-center justify-center">
            <i class="fas fa-list text-white text-xs"></i>
          </div>
          <span class="text-sm font-medium text-[#4a4a4a]">All Bookings</span>
          <i class="fas fa-chevron-right text-[#C4923A] text-xs ml-auto"></i>
        </a>
      </div>
    </div>
  </div>

</div>

@endsection
