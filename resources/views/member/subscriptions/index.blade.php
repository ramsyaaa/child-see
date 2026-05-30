@extends('member.layout.master')
@section('title', 'My Subscriptions')
@section('page-title', 'My Subscriptions')

@section('content')

{{-- Page header --}}
<div class="flex items-center justify-between mb-6">
  <div>
    <h1 class="text-xl font-semibold text-[#1a1a1a]" style="font-family:'Playfair Display',serif">My Subscriptions</h1>
    <p class="text-xs text-[#7a7a7a] mt-0.5">Your active and past membership packages</p>
  </div>
  <a href="{{ route('member.products.index') }}" class="btn-grad btn-grad-sm">
    <i class="fas fa-plus mr-1"></i> Buy Package
  </a>
</div>

@if($subscriptions->count())
  <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    @foreach($subscriptions as $subscription)
      <div class="dash-card">
        {{-- Header --}}
        <div class="flex items-start justify-between mb-4">
          <div>
            <h3 class="font-semibold text-[#1a1a1a] text-sm" style="font-family:'Playfair Display',serif">
              {{ $subscription->product->name }}
            </h3>
            <p class="text-xs text-[#7a7a7a] mt-0.5">
              {{ $subscription->start_date->format('d M Y') }} – {{ $subscription->end_date->format('d M Y') }}
            </p>
          </div>
          @if($subscription->status == 'active')
            <span class="badge-active">Active</span>
          @elseif($subscription->status == 'expired')
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-gray-100 text-gray-500">Expired</span>
          @else
            <span class="badge-full">Cancelled</span>
          @endif
        </div>

        {{-- Quota --}}
        @if($subscription->quota_allocated)
          @php $pct = min(100, round(($subscription->quota_used / $subscription->quota_allocated) * 100)); @endphp
          <div class="mb-4">
            <div class="flex justify-between text-xs text-[#7a7a7a] mb-1.5">
              <span>{{ $subscription->quota_used }} used</span>
              <span>{{ $subscription->getRemainingQuota() }} remaining of {{ $subscription->quota_allocated }}</span>
            </div>
            <div class="h-2 rounded-full bg-[#f0ebe0] overflow-hidden">
              <div class="h-full rounded-full transition-all" style="width:{{ $pct }}%; background:linear-gradient(90deg,#C4923A,#B85C38)"></div>
            </div>
          </div>
        @else
          <div class="mb-4 flex items-center gap-2 text-xs text-[#7a7a7a]">
            <i class="fas fa-infinity text-[#C4923A]"></i>
            <span>Unlimited classes</span>
          </div>
        @endif

        <a href="{{ route('member.subscriptions.show', $subscription) }}"
           class="inline-flex items-center gap-1.5 text-xs text-[#C4923A] hover:text-[#B85C38] font-medium transition-colors">
          View Details <i class="fas fa-arrow-right text-[10px]"></i>
        </a>
      </div>
    @endforeach
  </div>

  @if($subscriptions->hasPages())
    <div class="mt-4">{{ $subscriptions->links() }}</div>
  @endif

@else
  <div class="dash-card text-center py-16">
    <div class="w-14 h-14 bg-[#f5f0e8] rounded-full flex items-center justify-center mx-auto mb-3">
      <i class="fas fa-ticket-alt text-[#C4923A] text-xl"></i>
    </div>
    <p class="text-sm text-[#7a7a7a] mb-4">No subscriptions yet</p>
    <a href="{{ route('member.products.index') }}" class="btn-grad btn-grad-sm">Get a Package</a>
  </div>
@endif

@endsection
