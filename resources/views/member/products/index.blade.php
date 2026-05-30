@extends('member.layout.master')
@section('title', 'Buy Packages')
@section('page-title', 'Buy Packages')

@section('content')

<div class="mb-6">
  <h1 class="text-xl font-semibold text-[#1a1a1a]" style="font-family:'Playfair Display',serif">Buy Packages</h1>
  <p class="text-xs text-[#7a7a7a] mt-0.5">Choose a package that fits your wellness journey</p>
</div>

{{-- Filter --}}
<form method="GET" class="mb-6">
  <div class="flex flex-wrap gap-2">
    @foreach([''=>'All Types','subscription'=>'Subscription','dropin'=>'Drop-in','bundle'=>'Bundle'] as $val => $label)
      <a href="{{ request()->fullUrlWithQuery(['type' => $val]) }}"
         class="px-4 py-1.5 rounded-full text-xs font-semibold border transition-all
           {{ request('type', '') === $val
              ? 'bg-[#C4923A] border-[#C4923A] text-white'
              : 'border-[#e8e0d0] text-[#4a4a4a] hover:border-[#C4923A] hover:text-[#C4923A]' }}">
        {{ $label }}
      </a>
    @endforeach
  </div>
</form>

@if($products->count())
  <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
    @foreach($products as $product)
      <div class="dash-card relative flex flex-col">
        @if($product->is_popular)
          <div class="absolute -top-3 left-1/2 -translate-x-1/2">
            <span class="bg-[#C4923A] text-white text-[10px] font-bold px-3 py-1 rounded-full flex items-center gap-1">
              <i class="fas fa-star text-[8px]"></i> Most Popular
            </span>
          </div>
        @endif

        <div class="flex items-start justify-between mb-3 mt-2">
          <div class="flex-1 min-w-0 pr-2">
            <h3 class="font-semibold text-[#1a1a1a] text-sm leading-snug" style="font-family:'Playfair Display',serif">
              {{ $product->name }}
            </h3>
          </div>
          <span class="flex-shrink-0 text-[10px] px-2.5 py-0.5 rounded-full font-semibold
            {{ $product->type == 'subscription' ? 'bg-purple-100 text-purple-700'
               : ($product->type == 'dropin' ? 'bg-blue-100 text-blue-700' : 'bg-amber-100 text-amber-700') }}">
            {{ ucfirst($product->type) }}
          </span>
        </div>

        @if($product->description)
          <p class="text-xs text-[#7a7a7a] mb-3 leading-relaxed">{{ Str::limit($product->description, 80) }}</p>
        @endif

        <div class="mb-3">
          <span class="text-2xl font-semibold text-[#1a1a1a]" style="font-family:'Playfair Display',serif">
            Rp {{ number_format($product->price, 0, ',', '.') }}
          </span>
        </div>

        <ul class="space-y-1.5 mb-4 text-xs text-[#4a4a4a] flex-1">
          @if($product->quota_type === 'unlimited')
            <li class="flex items-center gap-2"><i class="fas fa-infinity text-[#C4923A] w-3"></i> Unlimited classes</li>
          @elseif($product->quota)
            <li class="flex items-center gap-2"><i class="fas fa-check text-[#C4923A] w-3"></i> {{ $product->quota }} sessions</li>
          @endif
          @if($product->duration_days)
            <li class="flex items-center gap-2"><i class="fas fa-calendar text-[#C4923A] w-3"></i> Valid {{ $product->duration_days }} days</li>
          @endif
        </ul>

        <div class="flex gap-2 mt-auto">
          <a href="{{ route('member.products.show', $product) }}"
             class="flex-1 text-center text-xs py-2 rounded-full border border-[#e8e0d0] text-[#4a4a4a] hover:border-[#C4923A] hover:text-[#C4923A] transition-all font-medium">
            Details
          </a>
          <form action="{{ route('member.cart.store') }}" method="POST" class="flex-1">
            @csrf
            <input type="hidden" name="product_id" value="{{ $product->id }}">
            <button type="submit" class="w-full btn-grad text-xs py-2 rounded-full">
              <i class="fas fa-shopping-cart mr-1"></i> Add to Cart
            </button>
          </form>
        </div>
      </div>
    @endforeach
  </div>

  @if($products->hasPages())
    <div class="mt-4">{{ $products->links() }}</div>
  @endif

@else
  <div class="dash-card text-center py-16">
    <div class="w-14 h-14 bg-[#f5f0e8] rounded-full flex items-center justify-center mx-auto mb-3">
      <i class="fas fa-shopping-bag text-[#C4923A] text-xl"></i>
    </div>
    <p class="text-sm text-[#7a7a7a]">No packages available at the moment</p>
  </div>
@endif

@endsection
