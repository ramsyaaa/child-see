@extends('member.layout.master')
@section('title', 'Checkout')
@section('page-title', 'Checkout')

@section('content')

@if(session('error'))
  <div class="mb-4 p-4 bg-red-50 border border-red-200 text-red-700 rounded-xl text-sm flex items-center gap-2">
    <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
  </div>
@endif

@if($errors->any())
  <div class="mb-4 p-4 bg-red-50 border border-red-200 text-red-700 rounded-xl text-sm">
    <i class="fas fa-exclamation-circle mr-1"></i>
    @foreach($errors->all() as $error)<div>{{ $error }}</div>@endforeach
  </div>
@endif

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

  {{-- Payment Form --}}
  <div class="lg:col-span-2 dash-card">
    <h3 class="font-semibold text-[#1a1a1a] mb-6" style="font-family:'Playfair Display',serif">
      <i class="fas fa-university mr-2 text-[#C4923A]"></i>Bank Transfer Payment
    </h3>

    <form action="{{ route('member.checkout.process') }}" method="POST" enctype="multipart/form-data" id="checkout-form">
      @csrf
      <input type="hidden" name="payment_method" value="offline">
      <input type="hidden" name="coupon_code" id="applied-coupon-code" value="">

      {{-- Bank Account Selection --}}
      <div class="mb-6">
        <label class="block text-xs font-semibold text-[#4a4a4a] uppercase tracking-wider mb-3">
          Transfer To <span class="text-red-500">*</span>
        </label>
        <div class="space-y-3" id="bank-list">
          @forelse($bankAccounts as $bank)
            <label class="flex items-center gap-4 p-4 rounded-xl border-2 cursor-pointer transition-all
                          {{ $loop->first ? 'border-[#C4923A] bg-[#fdf6ec]' : 'border-[#e8e0d0] bg-white hover:border-[#C4923A]' }}"
                   id="bank-label-{{ $bank->id }}">
              <input type="radio" name="bank_account_id" value="{{ $bank->id }}"
                     class="bank-radio" {{ $loop->first ? 'checked' : '' }}
                     onchange="selectBank({{ $bank->id }})">
              <div class="w-10 h-10 rounded-xl bg-[#f5f0e8] flex items-center justify-center flex-shrink-0">
                <i class="fas fa-university text-[#C4923A]"></i>
              </div>
              <div>
                <div class="font-semibold text-sm text-[#1a1a1a]">{{ $bank->bank_name }}</div>
                <div class="text-xs text-[#7a7a7a]">{{ $bank->account_number }} · {{ $bank->account_holder }}</div>
                @if($bank->note)
                  <div class="text-xs text-[#C4923A] mt-0.5">{{ $bank->note }}</div>
                @endif
              </div>
            </label>
          @empty
            <div class="p-4 rounded-xl bg-amber-50 border border-amber-200 text-amber-800 text-sm">
              <i class="fas fa-exclamation-triangle mr-2"></i>
              No bank accounts configured. Please contact admin.
            </div>
          @endforelse
        </div>
        @error('bank_account_id')
          <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
        @enderror
      </div>

      {{-- Upload Payment Proof --}}
      <div class="mb-6">
        <label class="block text-xs font-semibold text-[#4a4a4a] uppercase tracking-wider mb-2">
          Upload Transfer Proof <span class="text-red-500">*</span>
        </label>
        <div class="border-2 border-dashed border-[#e8e0d0] rounded-xl p-6 text-center hover:border-[#C4923A] transition-colors relative"
             id="drop-zone">
          <input type="file" name="payment_proof" id="payment_proof" accept="image/jpeg,image/png,image/webp"
                 class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" required
                 onchange="previewFile(this)">
          <div id="upload-placeholder">
            <div class="w-12 h-12 bg-[#f5f0e8] rounded-full flex items-center justify-center mx-auto mb-3">
              <i class="fas fa-cloud-upload-alt text-[#C4923A] text-xl"></i>
            </div>
            <p class="text-sm text-[#4a4a4a] font-medium">Click to upload or drag & drop</p>
            <p class="text-xs text-[#9ca3af] mt-1">JPG, PNG, WebP — max 2 MB</p>
          </div>
          <div id="upload-preview" class="hidden">
            <img id="preview-img" src="" alt="Preview" class="max-h-40 mx-auto rounded-lg mb-2">
            <p id="preview-name" class="text-xs text-[#7a7a7a]"></p>
          </div>
        </div>
        @error('payment_proof')
          <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
        @enderror
      </div>

      {{-- Instructions --}}
      <div class="p-4 bg-[#fdf6ec] rounded-xl border border-[#C4923A]/20 mb-6 text-sm text-[#4a4a4a] space-y-1">
        <p class="font-semibold text-[#B85C38] mb-2"><i class="fas fa-info-circle mr-1"></i>How to pay:</p>
        <p>1. Transfer the <strong>exact total</strong> shown in the order summary (after discount).</p>
        <p>2. Take a screenshot of your transfer confirmation.</p>
        <p>3. Upload the screenshot above and click <strong>Complete Order</strong>.</p>
        <p>4. Wait for admin verification (usually within 1–24 hours).</p>
      </div>

      <div class="flex gap-3">
        <a href="{{ route('member.cart.index') }}" class="flex items-center gap-2 px-5 py-2.5 rounded-full border border-[#e8e0d0] text-sm font-medium text-[#4a4a4a] hover:bg-[#f5f0e8] transition-colors">
          <i class="fas fa-arrow-left text-xs"></i> Back to Cart
        </a>
        <button type="submit" class="btn-grad flex-1 py-2.5 text-sm font-semibold" id="submit-btn">
          <i class="fas fa-check mr-2"></i>Complete Order
        </button>
      </div>
    </form>
  </div>

  {{-- Order Summary --}}
  <div class="space-y-4">
    <div class="dash-card">
      <h3 class="font-semibold text-[#1a1a1a] mb-4" style="font-family:'Playfair Display',serif">Order Summary</h3>
      <div class="flex items-start gap-3 mb-4">
        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-[#C4923A] to-[#B85C38] flex items-center justify-center flex-shrink-0">
          <i class="fas fa-ticket-alt text-white text-sm"></i>
        </div>
        <div>
          <p class="font-semibold text-sm text-[#1a1a1a]">{{ $cart->product->name }}</p>
          <p class="text-xs text-[#7a7a7a]">{{ $cart->product->description ? Str::limit($cart->product->description, 60) : ucfirst($cart->product->type) }}</p>
          @if($cart->product->quota_type === 'unlimited')
            <p class="text-xs text-[#C4923A] mt-0.5"><i class="fas fa-infinity mr-1"></i>Unlimited sessions</p>
          @elseif($cart->product->quota)
            <p class="text-xs text-[#C4923A] mt-0.5"><i class="fas fa-check mr-1"></i>{{ $cart->product->quota }} sessions</p>
          @endif
          @if($cart->product->duration_days)
            <p class="text-xs text-[#7a7a7a]"><i class="fas fa-calendar mr-1"></i>Valid {{ $cart->product->duration_days }} days</p>
          @endif
        </div>
      </div>

      {{-- Coupon Input --}}
      <div class="border-t border-[#f0ebe0] pt-4 mb-2">
        <p class="text-xs font-semibold text-[#4a4a4a] uppercase tracking-wider mb-2">
          <i class="fas fa-tag mr-1 text-[#C4923A]"></i>Coupon Code
        </p>
        <div class="flex gap-2">
          <input type="text" id="coupon-input" class="flex-1 px-3 py-2 text-sm border border-[#e8e0d0] rounded-lg focus:outline-none focus:border-[#C4923A] uppercase"
                 placeholder="Enter code" style="font-family:monospace"
                 oninput="this.value=this.value.toUpperCase()">
          <button type="button" id="apply-coupon-btn"
                  onclick="applyCoupon()"
                  class="px-3 py-2 text-xs font-semibold rounded-lg bg-[#f5f0e8] text-[#B85C38] border border-[#e8e0d0] hover:bg-[#fdf6ec] transition-colors whitespace-nowrap">
            Apply
          </button>
        </div>
        {{-- Feedback --}}
        <div id="coupon-success" class="hidden mt-2 flex items-center gap-2 text-xs text-green-700 bg-green-50 border border-green-200 rounded-lg px-3 py-2">
          <i class="fas fa-check-circle flex-shrink-0"></i>
          <span id="coupon-success-msg"></span>
          <button type="button" onclick="removeCoupon()" class="ml-auto text-green-700 hover:text-red-600">
            <i class="fas fa-times"></i>
          </button>
        </div>
        <div id="coupon-error" class="hidden mt-2 text-xs text-red-600 bg-red-50 border border-red-200 rounded-lg px-3 py-2">
          <i class="fas fa-exclamation-circle mr-1"></i><span id="coupon-error-msg"></span>
        </div>
      </div>

      {{-- Price Breakdown --}}
      <div class="border-t border-[#f0ebe0] pt-4 space-y-2 text-sm">
        <div class="flex justify-between">
          <span class="text-[#7a7a7a]">Subtotal</span>
          <span id="display-subtotal">Rp {{ number_format($cart->product->price, 0, ',', '.') }}</span>
        </div>
        <div class="flex justify-between text-green-600" id="discount-row" style="display:none!important">
          <span><i class="fas fa-tag mr-1"></i>Discount (<span id="discount-code-label"></span>)</span>
          <span>- Rp <span id="discount-amount-display">0</span></span>
        </div>
        <div class="flex justify-between font-semibold text-base pt-2 border-t border-[#f0ebe0]">
          <span>Total</span>
          <span class="text-[#B85C38]" id="display-total">Rp {{ number_format($cart->product->price, 0, ',', '.') }}</span>
        </div>
      </div>
    </div>

    <div class="dash-card text-sm text-[#7a7a7a] space-y-2">
      <p class="font-semibold text-[#1a1a1a] text-xs uppercase tracking-wider mb-2">After Payment</p>
      <div class="flex items-start gap-2">
        <i class="fas fa-clock text-[#C4923A] mt-0.5 flex-shrink-0"></i>
        <span>Your subscription activates within 1–24 hours after admin verification.</span>
      </div>
      <div class="flex items-start gap-2">
        <i class="fas fa-envelope text-[#C4923A] mt-0.5 flex-shrink-0"></i>
        <span>You'll see the status update in <a href="{{ route('member.transactions.index') }}" class="text-[#C4923A] underline">My Transactions</a>.</span>
      </div>
    </div>
  </div>

</div>

@push('scripts')
<script>
const SUBTOTAL = {{ $cart->product->price }};

function formatRp(amount) {
  return 'Rp ' + Math.round(amount).toLocaleString('id-ID');
}

async function applyCoupon() {
  const code = document.getElementById('coupon-input').value.trim();
  if (!code) return;

  const btn = document.getElementById('apply-coupon-btn');
  btn.disabled = true;
  btn.textContent = '...';

  document.getElementById('coupon-success').classList.add('hidden');
  document.getElementById('coupon-error').classList.add('hidden');

  try {
    const resp = await fetch('{{ route('member.checkout.apply-coupon') }}', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': '{{ csrf_token() }}',
        'Accept': 'application/json',
      },
      body: JSON.stringify({ code: code, subtotal: SUBTOTAL }),
    });
    const data = await resp.json();

    if (data.valid) {
      // Show success
      document.getElementById('coupon-success-msg').textContent = data.message;
      document.getElementById('coupon-success').classList.remove('hidden');
      document.getElementById('coupon-error').classList.add('hidden');

      // Update hidden field
      document.getElementById('applied-coupon-code').value = data.code;

      // Update price display
      document.getElementById('discount-code-label').textContent = data.code;
      document.getElementById('discount-amount-display').textContent = Math.round(data.discount).toLocaleString('id-ID');
      document.getElementById('discount-row').style.removeProperty('display');
      document.getElementById('display-total').textContent = formatRp(data.final_total);

      btn.textContent = 'Applied';
      btn.disabled = true;
    } else {
      document.getElementById('coupon-error-msg').textContent = data.message;
      document.getElementById('coupon-error').classList.remove('hidden');
      btn.disabled = false;
      btn.textContent = 'Apply';
    }
  } catch (e) {
    document.getElementById('coupon-error-msg').textContent = 'Something went wrong. Please try again.';
    document.getElementById('coupon-error').classList.remove('hidden');
    btn.disabled = false;
    btn.textContent = 'Apply';
  }
}

function removeCoupon() {
  document.getElementById('coupon-input').value = '';
  document.getElementById('applied-coupon-code').value = '';
  document.getElementById('coupon-success').classList.add('hidden');
  document.getElementById('discount-row').style.setProperty('display', 'none', 'important');
  document.getElementById('display-total').textContent = formatRp(SUBTOTAL);
  document.getElementById('apply-coupon-btn').disabled = false;
  document.getElementById('apply-coupon-btn').textContent = 'Apply';
}

// Allow pressing Enter in the coupon field
document.getElementById('coupon-input').addEventListener('keydown', function(e) {
  if (e.key === 'Enter') { e.preventDefault(); applyCoupon(); }
});

function selectBank(id) {
  document.querySelectorAll('[id^="bank-label-"]').forEach(el => {
    el.classList.remove('border-[#C4923A]', 'bg-[#fdf6ec]');
    el.classList.add('border-[#e8e0d0]', 'bg-white');
  });
  const lbl = document.getElementById('bank-label-' + id);
  if (lbl) {
    lbl.classList.remove('border-[#e8e0d0]', 'bg-white');
    lbl.classList.add('border-[#C4923A]', 'bg-[#fdf6ec]');
  }
}

function previewFile(input) {
  if (input.files && input.files[0]) {
    const file = input.files[0];
    const reader = new FileReader();
    reader.onload = e => {
      document.getElementById('preview-img').src = e.target.result;
      document.getElementById('preview-name').textContent = file.name + ' (' + (file.size/1024).toFixed(1) + ' KB)';
      document.getElementById('upload-placeholder').classList.add('hidden');
      document.getElementById('upload-preview').classList.remove('hidden');
    };
    reader.readAsDataURL(file);
  }
}

document.getElementById('checkout-form').addEventListener('submit', function() {
  const btn = document.getElementById('submit-btn');
  btn.disabled = true;
  btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Processing...';
});
</script>
@endpush
@endsection
