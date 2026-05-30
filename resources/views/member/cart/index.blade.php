@extends('member.layout.master')
@section('title', 'Shopping Cart')
@section('page-title', 'Shopping Cart')

@section('content')

<div class="mb-6">
  <h1 class="text-xl font-semibold text-[#1a1a1a]" style="font-family:'Playfair Display',serif">Shopping Cart</h1>
  <p class="text-xs text-[#7a7a7a] mt-0.5">Review your selected package</p>
</div>

@if(session('success'))
  <div class="mb-4 flex items-start gap-3 bg-green-50 border border-green-200 text-green-800 text-sm px-4 py-3 rounded-xl">
    <i class="fas fa-check-circle mt-0.5 flex-shrink-0"></i>
    <span>{{ session('success') }}</span>
  </div>
@endif

@if($cart)
  <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

    {{-- Cart item --}}
    <div class="lg:col-span-2">
      <div class="dash-card">
        <h2 class="font-semibold text-[#1a1a1a] text-sm mb-4" style="font-family:'Playfair Display',serif">Cart Items</h2>

        <div class="flex items-start gap-4 py-4 border-b border-[#f0ebe0]">
          <div class="w-12 h-12 rounded-xl bg-[#fdf6ec] flex items-center justify-center flex-shrink-0">
            <i class="fas fa-{{ $cart->product->type == 'subscription' ? 'ticket-alt' : 'shopping-bag' }} text-[#C4923A]"></i>
          </div>
          <div class="flex-1 min-w-0">
            <div class="flex items-start justify-between gap-2">
              <div>
                <p class="font-semibold text-sm text-[#1a1a1a]">{{ $cart->product->name }}</p>
                <span class="text-[10px] px-2 py-0.5 rounded-full font-semibold mt-1 inline-block
                  {{ $cart->product->type == 'subscription' ? 'bg-purple-100 text-purple-700' : 'bg-blue-100 text-blue-700' }}">
                  {{ ucfirst($cart->product->type) }}
                </span>
              </div>
              <span class="text-base font-semibold text-[#1a1a1a] flex-shrink-0" style="font-family:'Playfair Display',serif">
                Rp {{ number_format($cart->product->price, 0, ',', '.') }}
              </span>
            </div>
            @if($cart->product->description)
              <p class="text-xs text-[#7a7a7a] mt-2 leading-relaxed">{{ Str::limit($cart->product->description, 80) }}</p>
            @endif
          </div>
        </div>

        <div class="flex items-center justify-between mt-4">
          <form action="{{ route('member.cart.destroy') }}" method="POST"
                onsubmit="return confirm('Remove this item from cart?');">
            @csrf
            @method('DELETE')
            <button type="submit"
              class="text-xs text-red-500 hover:text-red-700 border border-red-200 hover:border-red-400 px-3 py-1.5 rounded-full transition-colors">
              <i class="fas fa-trash mr-1"></i> Remove
            </button>
          </form>
          <a href="{{ route('member.products.index') }}"
             class="text-xs text-[#7a7a7a] hover:text-[#C4923A] transition-colors">
            <i class="fas fa-arrow-left mr-1"></i> Continue Shopping
          </a>
        </div>
      </div>
    </div>

    {{-- Order summary --}}
    <div>
      <div class="dash-card">
        <h2 class="font-semibold text-[#1a1a1a] text-sm mb-4" style="font-family:'Playfair Display',serif">Order Summary</h2>
        <div class="space-y-3 text-sm">
          <div class="flex justify-between">
            <span class="text-[#7a7a7a]">Subtotal</span>
            <span class="font-medium text-[#1a1a1a]">Rp {{ number_format($cart->product->price, 0, ',', '.') }}</span>
          </div>
        </div>
        <div class="border-t border-[#f0ebe0] my-4"></div>
        <div class="flex justify-between font-semibold mb-5">
          <span class="text-[#1a1a1a]">Total</span>
          <span class="text-[#C4923A]">Rp {{ number_format($cart->product->price, 0, ',', '.') }}</span>
        </div>
        <a href="{{ route('member.checkout.index') }}" class="block w-full btn-grad text-center py-3">
          Proceed to Checkout <i class="fas fa-arrow-right ml-1"></i>
        </a>
      </div>
    </div>

  </div>

@else
  <div class="dash-card text-center py-16">
    <div class="w-14 h-14 bg-[#f5f0e8] rounded-full flex items-center justify-center mx-auto mb-3">
      <i class="fas fa-shopping-cart text-[#C4923A] text-xl"></i>
    </div>
    <p class="text-sm text-[#7a7a7a] mb-4">Your cart is empty</p>
    <a href="{{ route('member.products.index') }}" class="btn-grad btn-grad-sm">Browse Packages</a>
  </div>
@endif

@endsection
