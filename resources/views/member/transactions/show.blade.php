@extends('member.layout.master')
@section('title', 'Transaction Details')
@section('page-title', 'Transaction Details')

@section('content')

@if(session('success'))
  <div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-700 rounded-xl text-sm flex items-center gap-2">
    <i class="fas fa-check-circle"></i> {{ session('success') }}
  </div>
@endif

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

  {{-- Transaction details --}}
  <div class="lg:col-span-2 dash-card">
    <div class="flex items-center justify-between mb-6">
      <h3 class="font-semibold text-[#1a1a1a]" style="font-family:'Playfair Display',serif">
        {{ $transaction->transaction_number }}
      </h3>
      @php
        $status = $transaction->payment_status;
        $badgeMap = [
          'pending'  => ['bg-amber-100 text-amber-800',  'fa-clock',        'Pending Verification'],
          'paid'     => ['bg-green-100 text-green-800',  'fa-check-circle', 'Verified'],
          'failed'   => ['bg-red-100 text-red-800',      'fa-times-circle', 'Rejected'],
          'rejected' => ['bg-red-100 text-red-800',      'fa-times-circle', 'Rejected'],
          'verified' => ['bg-green-100 text-green-800',  'fa-check-circle', 'Verified'],
        ];
        [$badgeClass, $icon, $label] = $badgeMap[$status] ?? ['bg-gray-100 text-gray-600', 'fa-circle', ucfirst($status)];
      @endphp
      <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold {{ $badgeClass }}">
        <i class="fas {{ $icon }}"></i> {{ $label }}
      </span>
    </div>

    {{-- Meta --}}
    <div class="grid grid-cols-2 gap-4 mb-6 text-sm">
      <div class="p-3 bg-[#faf7f2] rounded-xl">
        <p class="text-[#9ca3af] text-xs uppercase tracking-wider mb-1">Date</p>
        <p class="font-medium text-[#1a1a1a]">{{ $transaction->created_at->format('d M Y, H:i') }}</p>
      </div>
      <div class="p-3 bg-[#faf7f2] rounded-xl">
        <p class="text-[#9ca3af] text-xs uppercase tracking-wider mb-1">Payment Method</p>
        <p class="font-medium text-[#1a1a1a]">{{ ucfirst($transaction->payment_method) }} Transfer</p>
      </div>
      @if($transaction->bankAccount)
        <div class="p-3 bg-[#faf7f2] rounded-xl col-span-2">
          <p class="text-[#9ca3af] text-xs uppercase tracking-wider mb-1">Transferred To</p>
          <p class="font-medium text-[#1a1a1a]">
            {{ $transaction->bankAccount->bank_name }} — {{ $transaction->bankAccount->account_number }}
            <span class="text-[#7a7a7a] text-xs">({{ $transaction->bankAccount->account_holder }})</span>
          </p>
        </div>
      @endif
    </div>

    {{-- Payment proof --}}
    @if($transaction->payment_proof)
      <div class="mb-6">
        <p class="text-xs font-semibold text-[#4a4a4a] uppercase tracking-wider mb-2">Payment Proof</p>
        <img src="{{ asset('storage/' . $transaction->payment_proof) }}"
             alt="Payment Proof"
             class="rounded-xl border border-[#e8e0d0] max-h-64 object-contain bg-[#faf7f2]">
      </div>
    @endif

    {{-- Items --}}
    <div>
      <p class="text-xs font-semibold text-[#4a4a4a] uppercase tracking-wider mb-3">Items Purchased</p>
      <div class="space-y-3">
        @foreach($transaction->items as $item)
          <div class="flex items-center gap-4 p-4 bg-[#faf7f2] rounded-xl">
            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-[#C4923A] to-[#B85C38] flex items-center justify-center flex-shrink-0">
              <i class="fas fa-ticket-alt text-white text-sm"></i>
            </div>
            <div class="flex-1">
              <p class="font-semibold text-sm text-[#1a1a1a]">
                {{ $item->product_name_snapshot ?? $item->product->name ?? 'Package' }}
              </p>
              <p class="text-xs text-[#7a7a7a]">Qty: {{ $item->quantity }}</p>
            </div>
            <div class="text-right">
              <p class="font-semibold text-sm">Rp {{ number_format($item->unit_price ?? $item->subtotal, 0, ',', '.') }}</p>
            </div>
          </div>
        @endforeach
      </div>

      <div class="mt-4 pt-4 border-t border-[#e8e0d0] space-y-2 text-sm">
        @php $subtotalRaw = $transaction->items->sum('unit_price'); @endphp
        @if($transaction->discount_amount > 0)
          <div class="flex justify-between text-[#7a7a7a]">
            <span>Subtotal</span>
            <span>Rp {{ number_format($subtotalRaw, 0, ',', '.') }}</span>
          </div>
          <div class="flex justify-between text-green-700">
            <span>
              <i class="fas fa-tag mr-1"></i>
              Discount
              @if($transaction->coupon_code)
                <span class="font-mono text-xs ml-1 px-1.5 py-0.5 bg-green-100 rounded">{{ $transaction->coupon_code }}</span>
              @endif
            </span>
            <span>- Rp {{ number_format($transaction->discount_amount, 0, ',', '.') }}</span>
          </div>
        @endif
        <div class="flex justify-between items-center pt-2 border-t border-[#e8e0d0]">
          <span class="font-semibold text-[#1a1a1a]">Total Paid</span>
          <span class="text-xl font-semibold text-[#B85C38]" style="font-family:'Playfair Display',serif">
            Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}
          </span>
        </div>
      </div>
    </div>

    <div class="mt-6">
      <a href="{{ route('member.transactions.index') }}" class="inline-flex items-center gap-2 text-sm text-[#4a4a4a] hover:text-[#B85C38] transition-colors">
        <i class="fas fa-arrow-left text-xs"></i> Back to Transactions
      </a>
    </div>
  </div>

  {{-- Status card --}}
  <div class="space-y-4">
    <div class="dash-card">
      <h3 class="font-semibold text-[#1a1a1a] mb-4" style="font-family:'Playfair Display',serif">Payment Status</h3>

      @if($status === 'pending')
        <div class="p-4 bg-amber-50 border border-amber-200 rounded-xl text-sm text-amber-800">
          <p class="font-semibold mb-1"><i class="fas fa-clock mr-1"></i>Awaiting Verification</p>
          <p class="text-xs">Your payment proof has been submitted. Admin will verify it within 1–24 hours.</p>
        </div>
      @elseif(in_array($status, ['paid', 'verified']))
        <div class="p-4 bg-green-50 border border-green-200 rounded-xl text-sm text-green-800">
          <p class="font-semibold mb-1"><i class="fas fa-check-circle mr-1"></i>Payment Verified</p>
          <p class="text-xs">Your subscription is now active. Go to <a href="{{ route('member.subscriptions.index') }}" class="underline">My Subscriptions</a> to use it.</p>
        </div>
        @if($transaction->verified_at)
          <p class="text-xs text-[#9ca3af] mt-2">Verified on {{ \Carbon\Carbon::parse($transaction->verified_at)->format('d M Y, H:i') }}</p>
        @endif
      @elseif(in_array($status, ['failed', 'rejected']))
        <div class="p-4 bg-red-50 border border-red-200 rounded-xl text-sm text-red-800">
          <p class="font-semibold mb-1"><i class="fas fa-times-circle mr-1"></i>Payment Rejected</p>
          @if($transaction->rejection_reason)
            <p class="text-xs mt-1"><strong>Reason:</strong> {{ $transaction->rejection_reason }}</p>
          @endif
          <p class="text-xs mt-2">Please contact us or try placing a new order.</p>
        </div>
      @endif

      <div class="mt-4 space-y-2">
        <a href="{{ route('member.subscriptions.index') }}" class="flex items-center gap-2 text-sm text-[#C4923A] hover:text-[#B85C38] transition-colors">
          <i class="fas fa-ticket-alt text-xs"></i> View My Subscriptions
        </a>
        <a href="{{ route('member.products.index') }}" class="flex items-center gap-2 text-sm text-[#4a4a4a] hover:text-[#B85C38] transition-colors">
          <i class="fas fa-shopping-bag text-xs"></i> Browse Packages
        </a>
      </div>
    </div>

    <div class="dash-card text-xs text-[#7a7a7a] space-y-1">
      <p class="font-semibold text-[#4a4a4a] mb-2 text-xs uppercase tracking-wider">Need Help?</p>
      <p>Contact us via WhatsApp or email if you have questions about this transaction.</p>
      <a href="https://wa.me/6281234567890" class="inline-flex items-center gap-1.5 text-[#C4923A] mt-2">
        <i class="fab fa-whatsapp"></i> Chat on WhatsApp
      </a>
    </div>
  </div>

</div>
@endsection
