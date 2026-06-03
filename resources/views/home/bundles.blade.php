@extends('home.layout.master')
@section('title', 'Bundles & Packages — Child See')

@section('content')

{{-- HERO --}}
<section class="py-16 px-6 text-center reveal">
  <div class="max-w-2xl mx-auto">
    <h1 class="text-5xl font-semibold mb-4" style="font-family:'Playfair Display',serif">Choose Your Journey</h1>
    <p class="text-[#7a7a7a] text-sm leading-relaxed">Flexible pricing options designed to support your wellness journey, whatever your pace</p>
  </div>
</section>

{{-- PRICING CARDS --}}
<section class="px-6 pb-20">
  <div class="max-w-5xl mx-auto">
    @if($products->count() > 0)
      <div class="grid grid-cols-1 md:grid-cols-3 gap-6 items-start">
        @foreach($products as $product)
          @if($product->is_popular)
            <div class="reveal relative text-white rounded-3xl p-8 shadow-2xl scale-105" style="background:#6B4226">
              <div class="absolute -top-4 left-1/2 -translate-x-1/2">
                <span class="bg-[#C4923A] text-white text-[10px] font-bold px-4 py-1.5 rounded-full flex items-center gap-1.5">
                  <i class="fas fa-star text-[8px]"></i> Most Popular
                </span>
              </div>
              <p class="text-sm font-medium mb-1 mt-2">{{ $product->name }}</p>
              <p class="text-xs text-white/60 mb-6">{{ $product->description ? Str::limit($product->description, 60) : ($product->quota_type === 'unlimited' ? 'Unlimited access' : $product->quota.' sessions') }}</p>
              <div class="mb-6">
                <span class="text-5xl font-semibold" style="font-family:'Playfair Display',serif">{{ number_format($product->price, 0, ',', '.') }}</span>
                <span class="text-white/60 text-xs ml-1">/ {{ $product->isDropIn() ? 'class' : 'package' }}</span>
              </div>
              <ul class="space-y-3 mb-8 text-sm text-white/80">
                @if($product->quota_type === 'unlimited')
                  <li class="flex items-center gap-2"><i class="fas fa-check text-[#C4923A] text-xs"></i> Unlimited class access</li>
                @else
                  <li class="flex items-center gap-2"><i class="fas fa-check text-[#C4923A] text-xs"></i> {{ $product->quota }} {{ $product->quota == 1 ? 'session' : 'sessions' }}</li>
                @endif
                @if($product->duration_days)
                  <li class="flex items-center gap-2"><i class="fas fa-check text-[#C4923A] text-xs"></i> Valid for {{ $product->duration_days }} days</li>
                @endif
                <li class="flex items-center gap-2"><i class="fas fa-check text-[#C4923A] text-xs"></i> Priority booking</li>
                <li class="flex items-center gap-2"><i class="fas fa-check text-[#C4923A] text-xs"></i> Access to all class types</li>
              </ul>
              <a href="{{ route('login') }}" class="block text-center bg-[#C4923A] text-white font-semibold text-sm py-3 rounded-full hover:bg-[#A07830] transition-all">Book Now</a>
            </div>
          @else
            <div class="reveal bg-white rounded-3xl border border-[#e8e0d0] p-8 hover:shadow-lg transition-shadow">
              <p class="text-sm font-medium text-[#4a4a4a] mb-1">{{ $product->name }}</p>
              <p class="text-xs text-[#7a7a7a] mb-6">{{ $product->description ? Str::limit($product->description, 60) : ($product->quota_type === 'unlimited' ? 'Unlimited' : $product->quota.' sessions') }}</p>
              <div class="mb-6">
                <span class="text-5xl font-semibold text-[#1a1a1a]" style="font-family:'Playfair Display',serif">{{ number_format($product->price, 0, ',', '.') }}</span>
                <span class="text-[#7a7a7a] text-xs ml-1">/ {{ $product->isDropIn() ? 'class' : 'package' }}</span>
              </div>
              <ul class="space-y-3 mb-8 text-sm text-[#4a4a4a]">
                @if($product->quota_type === 'unlimited')
                  <li class="flex items-center gap-2"><i class="fas fa-check text-[#C4923A] text-xs"></i> Unlimited class access</li>
                @else
                  <li class="flex items-center gap-2"><i class="fas fa-check text-[#C4923A] text-xs"></i> {{ $product->quota }} {{ $product->quota == 1 ? 'session' : 'sessions' }}</li>
                @endif
                @if($product->duration_days)
                  <li class="flex items-center gap-2"><i class="fas fa-check text-[#C4923A] text-xs"></i> Valid for {{ $product->duration_days }} days</li>
                @endif
                <li class="flex items-center gap-2"><i class="fas fa-check text-[#C4923A] text-xs"></i> Access to classes</li>
              </ul>
              <a href="{{ route('login') }}" class="block text-center bg-[#f5f0e8] text-[#B85C38] font-semibold text-sm py-3 rounded-full hover:bg-[#C4923A] hover:text-white transition-all">Book Now</a>
            </div>
          @endif
        @endforeach
      </div>
    @else
      {{-- Static fallback --}}
      <div class="grid grid-cols-1 md:grid-cols-3 gap-6 items-start">
        <div class="reveal bg-white rounded-3xl border border-[#e8e0d0] p-8 hover:shadow-lg transition-shadow">
          <p class="text-sm font-medium text-[#4a4a4a] mb-1">Fusion Pilates</p>
          <p class="text-xs text-[#7a7a7a] mb-6">4 sessions</p>
          <div class="mb-6"><span class="text-5xl font-semibold text-[#1a1a1a]" style="font-family:'Playfair Display',serif">310.000</span><span class="text-[#7a7a7a] text-xs ml-1">/ session</span></div>
          <ul class="space-y-3 mb-8 text-sm text-[#4a4a4a]">
            <li class="flex items-center gap-2"><i class="fas fa-check text-[#C4923A] text-xs"></i> Access to any mat pilates class</li>
            <li class="flex items-center gap-2"><i class="fas fa-check text-[#C4923A] text-xs"></i> Valid for 30 days</li>
            <li class="flex items-center gap-2"><i class="fas fa-check text-[#C4923A] text-xs"></i> 1 Reschedule</li>
          </ul>
          <a href="{{ route('login') }}" class="block text-center bg-[#f5f0e8] text-[#B85C38] font-semibold text-sm py-3 rounded-full hover:bg-[#C4923A] hover:text-white transition-all">Book Now</a>
        </div>
        <div class="reveal relative text-white rounded-3xl p-8 shadow-2xl scale-105" style="background:#6B4226">
          <div class="absolute -top-4 left-1/2 -translate-x-1/2"><span class="bg-[#C4923A] text-white text-[10px] font-bold px-4 py-1.5 rounded-full flex items-center gap-1.5"><i class="fas fa-star text-[8px]"></i> Most Popular</span></div>
          <p class="text-sm font-medium mb-1 mt-2">Flow &amp; Flex</p>
          <p class="text-xs text-white/60 mb-6">Best value — 4 Yoga + 1 Mat Pilates</p>
          <div class="mb-6"><span class="text-5xl font-semibold" style="font-family:'Playfair Display',serif">375.000</span><span class="text-white/60 text-xs ml-1">/ session</span></div>
          <ul class="space-y-3 mb-8 text-sm text-white/80">
            <li class="flex items-center gap-2"><i class="fas fa-check text-[#C4923A] text-xs"></i> Access to any class</li>
            <li class="flex items-center gap-2"><i class="fas fa-check text-[#C4923A] text-xs"></i> Valid for 30 days</li>
            <li class="flex items-center gap-2"><i class="fas fa-check text-[#C4923A] text-xs"></i> Priority booking</li>
          </ul>
          <a href="{{ route('login') }}" class="block text-center bg-[#C4923A] text-white font-semibold text-sm py-3 rounded-full hover:bg-[#A07830] transition-all">Book Now</a>
        </div>
        <div class="reveal bg-white rounded-3xl border border-[#e8e0d0] p-8 hover:shadow-lg transition-shadow">
          <p class="text-sm font-medium text-[#4a4a4a] mb-1">Ferensoul</p>
          <p class="text-xs text-[#7a7a7a] mb-6">Unlimited studio class access</p>
          <div class="mb-6"><span class="text-5xl font-semibold text-[#1a1a1a]" style="font-family:'Playfair Display',serif">930.000</span><span class="text-[#7a7a7a] text-xs ml-1">/ session</span></div>
          <ul class="space-y-3 mb-8 text-sm text-[#4a4a4a]">
            <li class="flex items-center gap-2"><i class="fas fa-check text-[#C4923A] text-xs"></i> Unlimited yoga class access</li>
            <li class="flex items-center gap-2"><i class="fas fa-check text-[#C4923A] text-xs"></i> Priority booking</li>
            <li class="flex items-center gap-2"><i class="fas fa-check text-[#C4923A] text-xs"></i> Valid for 30 days</li>
          </ul>
          <a href="{{ route('login') }}" class="block text-center bg-[#f5f0e8] text-[#B85C38] font-semibold text-sm py-3 rounded-full hover:bg-[#C4923A] hover:text-white transition-all">Book Now</a>
        </div>
      </div>
    @endif
  </div>
</section>

{{-- FAQ --}}
<section class="py-20 px-6 bg-[#faf7f2] border-t border-[#e8e0d0]">
  <div class="max-w-3xl mx-auto">
    <h2 class="text-4xl font-semibold text-center mb-12 reveal" style="font-family:'Playfair Display',serif">Frequently Asked Questions</h2>
    <div class="space-y-0 divide-y divide-[#e8e0d0]">
      @foreach([['Can I try a class before purchasing a package?','Yes, you can try our class without purchasing any bundles. You can simply buy a single visit to experience the class first.'],['What if I need to cancel or reschedule?','It depends on the package you choose. Please kindly check the terms and conditions of your selected package before booking.'],['Do you have a shower room?','Yes, we do. Our facilities include a shower room, prayer room, and hair dryer that you can use after class.'],['Are mats provided?','Yes, we provide mats for free if you need one.'],['What do I need to bring?','Please bring your own tumbler and any personal items you may need. Comfortable workout attire is recommended.']] as $faq)
        <div class="reveal">
          <button class="accordion-trigger w-full text-left py-5 flex justify-between items-center gap-4 text-[#1a1a1a] font-medium hover:text-[#B85C38] transition-colors">
            <span>{{ $faq[0] }}</span>
            <span class="acc-icon text-[#C4923A] text-xl font-light flex-shrink-0">+</span>
          </button>
          <div class="accordion-content text-sm text-[#7a7a7a] leading-relaxed pb-5">{{ $faq[1] }}</div>
        </div>
      @endforeach
    </div>
  </div>
</section>

{{-- WHATSAPP CTA --}}
<section class="py-20 px-6 bg-gradient-to-br from-[#8B4513] to-[#C4923A] text-white text-center">
  <div class="max-w-2xl mx-auto reveal">
    <h2 class="text-3xl font-semibold mb-3" style="font-family:'Playfair Display',serif">Still Have Questions?</h2>
    <p class="text-white/70 text-sm mb-8 leading-relaxed">We're here to help you find the perfect fit for your wellness journey. Feel free to reach out.</p>
    <a href="https://wa.me/6281234567890" class="inline-flex items-center gap-2 bg-white text-[#8B4513] px-8 py-3 rounded-full text-sm font-semibold hover:bg-[#f5f0e8] transition-colors">
      <i class="fab fa-whatsapp text-base"></i> Chat with Us on WhatsApp
    </a>
  </div>
</section>

@endsection
