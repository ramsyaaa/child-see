@extends('member.layout.master')
@section('title', $product->name)
@section('page-title', 'Package Details')

@section('content')

<div class="flex items-center gap-2 mb-6">
  <a href="{{ route('member.products.index') }}"
     class="w-8 h-8 rounded-full bg-[#f5f0e8] flex items-center justify-center text-[#4a4a4a] hover:text-[#B85C38] transition-colors">
    <i class="fas fa-arrow-left text-xs"></i>
  </a>
  <div>
    <h1 class="text-xl font-semibold text-[#1a1a1a]" style="font-family:'Playfair Display',serif">Package Details</h1>
  </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

  {{-- Main --}}
  <div class="lg:col-span-2">
    <div class="dash-card relative">
      @if($product->is_popular)
        <div class="absolute top-4 right-4">
          <span class="bg-[#C4923A] text-white text-[10px] font-bold px-3 py-1 rounded-full flex items-center gap-1">
            <i class="fas fa-star text-[8px]"></i> Most Popular
          </span>
        </div>
      @endif

      <div class="flex items-start gap-3 mb-4">
        <span class="text-[10px] px-2.5 py-0.5 rounded-full font-semibold mt-1
          {{ $product->type == 'subscription' ? 'bg-purple-100 text-purple-700'
             : ($product->type == 'dropin' ? 'bg-blue-100 text-blue-700' : 'bg-amber-100 text-amber-700') }}">
          {{ ucfirst($product->type) }}
        </span>
      </div>

      <h2 class="text-xl font-semibold text-[#1a1a1a] mb-2" style="font-family:'Playfair Display',serif">
        {{ $product->name }}
      </h2>

      <div class="mb-4">
        <span class="text-3xl font-semibold text-[#1a1a1a]" style="font-family:'Playfair Display',serif">
          Rp {{ number_format($product->price, 0, ',', '.') }}
        </span>
      </div>

      @if($product->description)
        <div class="mb-5">
          <h3 class="text-xs font-semibold text-[#7a7a7a] uppercase tracking-wider mb-2">Description</h3>
          <p class="text-sm text-[#4a4a4a] leading-relaxed">{{ $product->description }}</p>
        </div>
      @endif

      <div class="mb-6">
        <h3 class="text-xs font-semibold text-[#7a7a7a] uppercase tracking-wider mb-3">What's Included</h3>
        <ul class="space-y-2">
          @if($product->quota_type === 'unlimited')
            <li class="flex items-center gap-2 text-sm text-[#4a4a4a]">
              <i class="fas fa-infinity text-[#C4923A] w-4"></i> Unlimited class access
            </li>
          @elseif($product->quota)
            <li class="flex items-center gap-2 text-sm text-[#4a4a4a]">
              <i class="fas fa-check text-[#C4923A] w-4"></i> {{ $product->quota }} sessions
            </li>
          @endif
          @if($product->duration_days)
            <li class="flex items-center gap-2 text-sm text-[#4a4a4a]">
              <i class="fas fa-calendar text-[#C4923A] w-4"></i> Valid for {{ $product->duration_days }} days
            </li>
          @endif
          <li class="flex items-center gap-2 text-sm text-[#4a4a4a]">
            <i class="fas fa-check text-[#C4923A] w-4"></i> Access to all public classes
          </li>
          <li class="flex items-center gap-2 text-sm text-[#4a4a4a]">
            <i class="fas fa-check text-[#C4923A] w-4"></i> Book classes in advance
          </li>
        </ul>
      </div>

      <div class="flex gap-3">
        <form action="{{ route('member.cart.store') }}" method="POST" class="flex-1">
          @csrf
          <input type="hidden" name="product_id" value="{{ $product->id }}">
          <button type="submit" class="w-full btn-grad py-3">
            <i class="fas fa-shopping-cart mr-2"></i> Add to Cart
          </button>
        </form>
        <a href="{{ route('member.products.index') }}"
           class="px-6 py-3 rounded-full text-sm font-semibold border border-[#e8e0d0] text-[#4a4a4a] hover:border-[#C4923A] hover:text-[#C4923A] transition-all">
          Back
        </a>
      </div>
    </div>
  </div>

  {{-- Summary sidebar --}}
  <div>
    <div class="dash-card">
      <h3 class="font-semibold text-[#1a1a1a] mb-4 text-sm" style="font-family:'Playfair Display',serif">Package Summary</h3>
      <div class="space-y-3 text-sm">
        <div class="flex justify-between">
          <span class="text-[#7a7a7a]">Type</span>
          <span class="font-medium text-[#1a1a1a]">{{ ucfirst($product->type) }}</span>
        </div>
        <div class="flex justify-between">
          <span class="text-[#7a7a7a]">Price</span>
          <span class="font-semibold text-[#1a1a1a]">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
        </div>
        @if($product->duration_days)
          <div class="flex justify-between">
            <span class="text-[#7a7a7a]">Duration</span>
            <span class="font-medium text-[#1a1a1a]">{{ $product->duration_days }} days</span>
          </div>
        @endif
        <div class="flex justify-between">
          <span class="text-[#7a7a7a]">Sessions</span>
          <span class="font-medium text-[#1a1a1a]">{{ $product->quota ?? 'Unlimited' }}</span>
        </div>
      </div>
      <div class="border-t border-[#f0ebe0] my-4"></div>
      <div class="flex justify-between font-semibold text-sm">
        <span class="text-[#1a1a1a]">Total</span>
        <span class="text-[#C4923A]">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
      </div>
    </div>
  </div>

</div>

@endsection
