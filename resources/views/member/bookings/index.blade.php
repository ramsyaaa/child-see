@extends('member.layout.master')
@section('title', 'My Bookings')
@section('page-title', 'My Bookings')

@section('content')

{{-- Page header --}}
<div class="flex items-center justify-between mb-6">
  <div>
    <h1 class="text-xl font-semibold text-[#1a1a1a]" style="font-family:'Playfair Display',serif">My Bookings</h1>
    <p class="text-xs text-[#7a7a7a] mt-0.5">All your class reservations in one place</p>
  </div>
  <a href="{{ route('member.browse-classes.index') }}" class="btn-grad btn-grad-sm">
    <i class="fas fa-plus mr-1"></i> Book a Class
  </a>
</div>

@if(session('success'))
  <div class="mb-4 flex items-start gap-3 bg-green-50 border border-green-200 text-green-800 text-sm px-4 py-3 rounded-xl">
    <i class="fas fa-check-circle mt-0.5 flex-shrink-0"></i>
    <span>{{ session('success') }}</span>
  </div>
@endif

<div class="dash-card overflow-hidden p-0">
  <div class="px-6 py-4 border-b border-[#f0ebe0] flex items-center justify-between">
    <h2 class="font-semibold text-[#1a1a1a] text-sm" style="font-family:'Playfair Display',serif">All Bookings</h2>
    <span class="text-xs text-[#7a7a7a]">{{ $bookings->total() }} total</span>
  </div>

  @if($bookings->count())
    <div class="divide-y divide-[#f0ebe0]">
      @foreach($bookings as $booking)
        <div class="flex flex-col sm:flex-row sm:items-center gap-3 px-6 py-4">
          {{-- Date block --}}
          <div class="flex-shrink-0 text-center w-12">
            <div class="text-[10px] font-semibold text-[#7a7a7a] uppercase">
              {{ \Carbon\Carbon::parse($booking->batchClass->date)->format('D') }}
            </div>
            <div class="text-xl font-semibold text-[#B85C38]" style="font-family:'Playfair Display',serif">
              {{ \Carbon\Carbon::parse($booking->batchClass->date)->format('d') }}
            </div>
            <div class="text-[10px] text-[#7a7a7a]">
              {{ \Carbon\Carbon::parse($booking->batchClass->date)->format('M') }}
            </div>
          </div>

          {{-- Class info --}}
          <div class="flex-1 min-w-0">
            <p class="text-sm font-semibold text-[#1a1a1a] truncate">
              {{ $booking->batchClass->masterClass->name ?? 'Class' }}
            </p>
            <p class="text-xs text-[#7a7a7a] mt-0.5">
              {{ \Carbon\Carbon::parse($booking->batchClass->start_time)->format('H:i') }}
              · {{ $booking->batchClass->instructor->name ?? '' }}
              · {{ $booking->batchClass->room->room_name ?? 'Studio' }}
            </p>
          </div>

          {{-- Type --}}
          <div class="flex-shrink-0 hidden sm:block">
            <span class="text-xs px-2.5 py-1 rounded-full font-medium
              {{ $booking->booking_type == 'subscription' ? 'bg-[#ede9fe] text-purple-700' : 'bg-[#dbeafe] text-blue-700' }}">
              {{ ucfirst($booking->booking_type) }}
            </span>
          </div>

          {{-- Price --}}
          <div class="flex-shrink-0 hidden sm:block text-sm font-medium text-[#4a4a4a] w-24 text-right">
            @if($booking->price > 0)
              Rp {{ number_format($booking->price, 0, ',', '.') }}
            @else
              <span class="text-green-600 font-semibold">Free</span>
            @endif
          </div>

          {{-- Status + action --}}
          <div class="flex items-center gap-2 flex-shrink-0">
            @if($booking->status == 'booked')
              <span class="badge-open">Booked</span>
            @elseif($booking->status == 'cancelled')
              <span class="badge-full">Cancelled</span>
            @elseif($booking->status == 'completed')
              <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-gray-100 text-gray-600">Completed</span>
            @else
              <span class="badge-pending">{{ ucfirst($booking->status) }}</span>
            @endif

            @if($booking->status == 'booked' && $booking->batchClass->date > now())
              <form action="{{ route('member.bookings.cancel', $booking) }}" method="POST"
                    onsubmit="return confirm('Cancel this booking?');">
                @csrf
                @method('PUT')
                <button type="submit"
                  class="text-xs text-red-500 hover:text-red-700 border border-red-200 hover:border-red-400 px-2.5 py-1 rounded-full transition-colors">
                  Cancel
                </button>
              </form>
            @endif
          </div>
        </div>
      @endforeach
    </div>

    {{-- Pagination --}}
    @if($bookings->hasPages())
      <div class="px-6 py-4 border-t border-[#f0ebe0]">
        {{ $bookings->links() }}
      </div>
    @endif

  @else
    <div class="text-center py-16">
      <div class="w-14 h-14 bg-[#f5f0e8] rounded-full flex items-center justify-center mx-auto mb-3">
        <i class="fas fa-bookmark text-[#C4923A] text-xl"></i>
      </div>
      <p class="text-sm text-[#7a7a7a] mb-4">No bookings yet</p>
      <a href="{{ route('member.browse-classes.index') }}" class="btn-grad btn-grad-sm">Browse Classes</a>
    </div>
  @endif
</div>

@endsection
