@extends('member.layout.master')
@section('title', 'My Transactions')
@section('page-title', 'My Transactions')

@section('content')

<div class="mb-6">
  <h1 class="text-xl font-semibold text-[#1a1a1a]" style="font-family:'Playfair Display',serif">My Transactions</h1>
  <p class="text-xs text-[#7a7a7a] mt-0.5">Your payment history</p>
</div>

<div class="dash-card overflow-hidden p-0">
  <div class="px-6 py-4 border-b border-[#f0ebe0] flex items-center justify-between">
    <h2 class="font-semibold text-[#1a1a1a] text-sm" style="font-family:'Playfair Display',serif">All Transactions</h2>
    <span class="text-xs text-[#7a7a7a]">{{ $transactions->total() }} total</span>
  </div>

  @if($transactions->count())
    <div class="divide-y divide-[#f0ebe0]">
      @foreach($transactions as $transaction)
        <div class="flex flex-col sm:flex-row sm:items-center gap-3 px-6 py-4">
          {{-- Transaction number + date --}}
          <div class="flex-1 min-w-0">
            <p class="text-sm font-semibold text-[#1a1a1a]">{{ $transaction->transaction_number }}</p>
            <p class="text-xs text-[#7a7a7a] mt-0.5">{{ $transaction->created_at->format('d M Y, H:i') }}</p>
          </div>

          {{-- Amount --}}
          <div class="flex-shrink-0 text-sm font-semibold text-[#1a1a1a]">
            Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}
          </div>

          {{-- Payment method --}}
          <div class="flex-shrink-0 hidden sm:block">
            <span class="text-xs px-2.5 py-1 rounded-full font-medium bg-[#f5f0e8] text-[#7a7a7a]">
              {{ ucfirst($transaction->payment_method) }}
            </span>
          </div>

          {{-- Status --}}
          <div class="flex-shrink-0">
            @if($transaction->payment_status == 'paid')
              <span class="badge-active">Paid</span>
            @elseif($transaction->payment_status == 'pending')
              <span class="badge-pending">Pending</span>
            @elseif($transaction->payment_status == 'failed')
              <span class="badge-full">Failed</span>
            @else
              <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-gray-100 text-gray-500">
                {{ ucfirst($transaction->payment_status) }}
              </span>
            @endif
          </div>

          {{-- Action --}}
          <div class="flex-shrink-0">
            <a href="{{ route('member.transactions.show', $transaction) }}"
               class="text-xs text-[#C4923A] hover:text-[#B85C38] border border-[#e8d5b5] hover:border-[#C4923A] px-3 py-1.5 rounded-full transition-colors font-medium">
              View
            </a>
          </div>
        </div>
      @endforeach
    </div>

    @if($transactions->hasPages())
      <div class="px-6 py-4 border-t border-[#f0ebe0]">
        {{ $transactions->links() }}
      </div>
    @endif

  @else
    <div class="text-center py-16">
      <div class="w-14 h-14 bg-[#f5f0e8] rounded-full flex items-center justify-center mx-auto mb-3">
        <i class="fas fa-receipt text-[#C4923A] text-xl"></i>
      </div>
      <p class="text-sm text-[#7a7a7a]">No transactions yet</p>
    </div>
  @endif
</div>

@endsection
