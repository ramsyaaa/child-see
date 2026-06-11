@extends('member.layout.master')
@section('title', 'Mulai Asesmen Baru')
@section('page-title', 'Mulai Asesmen Baru')

@section('content')

@if($errors->any())
  <div class="mb-5 p-4 rounded-xl text-sm" style="background:#fee2e2;color:#991b1b;border:1px solid #fecaca">
    <div class="font-semibold mb-1 flex items-center gap-2"><i class="fas fa-exclamation-circle"></i> Periksa kembali:</div>
    <ul class="list-disc list-inside space-y-0.5">
      @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
    </ul>
  </div>
@endif

@php $children = $children ?? collect([$child])->filter(); @endphp

@if($children->isEmpty())
  {{-- No child: prompt to add --}}
  <div class="dash-card text-center py-16">
    <div class="w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-4" style="background:#F0EEF5">
      <i class="fas fa-child text-[#BAA6D6] text-3xl"></i>
    </div>
    <h3 class="font-semibold text-[#2E2046] mb-2" style="font-family:'Playfair Display',serif">Data Anak Belum Ada</h3>
    <p class="text-sm text-gray-500 mb-6 max-w-xs mx-auto">Tambahkan data anak terlebih dahulu sebelum melakukan asesmen.</p>
    <a href="{{ route('member.children.create') }}" class="btn-primary">
      <i class="fas fa-plus"></i> Tambah Data Anak
    </a>
  </div>
@else

  {{-- ── Informed Consent Modal ── --}}
  <div id="consentModal" class="fixed inset-0 z-50 flex items-center justify-center p-4" style="background:rgba(46,32,70,.75);backdrop-filter:blur(4px);">
    <div class="w-full max-w-2xl rounded-2xl shadow-2xl overflow-hidden" style="background:#fff;max-height:90vh;display:flex;flex-direction:column;">
      <div class="px-6 py-5 flex items-start gap-3" style="background:linear-gradient(135deg,#4A3769,#8E77AB);">
        <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0" style="background:rgba(255,255,255,.15)">
          <i class="fas fa-shield-alt text-white"></i>
        </div>
        <div>
          <h3 class="font-bold text-white text-lg" style="font-family:'Playfair Display',serif">Persetujuan Pengguna (Informed Consent)</h3>
          <p class="text-xs" style="color:rgba(255,255,255,.7);">Harap dibaca dan disetujui sebelum melanjutkan</p>
        </div>
      </div>
      <div class="overflow-y-auto px-6 py-5 flex-1 text-sm leading-relaxed" style="color:#374151;">
        <p class="mb-4">Melalui instrumen ini, Bapak/Ibu berpartisipasi dalam prosedur identifikasi awal yang bertujuan untuk memetakan profil fungsional serta kebutuhan belajar peserta didik demi penyusunan program pendidikan yang lebih inklusif dan akomodatif.</p>
        <p class="mb-4">Seluruh informasi yang diberikan bersifat <strong>rahasia</strong>, dilindungi sesuai dengan prinsip etika data, dan hanya akan dipergunakan oleh pihak berwenang untuk kepentingan pengembangan layanan akademik peserta didik yang bersangkutan.</p>
        <p class="mb-4">Partisipasi dalam pengisian ini bersifat <strong>sukarela</strong>, di mana responden berhak untuk mendapatkan penjelasan lengkap serta dapat menghentikan proses pengisian kapan saja tanpa adanya konsekuensi administratif maupun akademis.</p>
        <p class="mb-4">Namun, perlu dipahami secara saksama bahwa hasil dari instrumen ini merupakan bentuk <strong>skrining awal (early identification)</strong> dan <strong>bukan merupakan diagnosis medis atau psikologis formal</strong>; diagnosis komprehensif hanya dapat ditegakkan oleh tenaga profesional terkait.</p>
        <p>Dengan menyetujui pernyataan ini, Bapak/Ibu menyatakan telah memahami seluruh penjelasan di atas dan memberikan izin secara sadar atas pengolahan data guna mendukung optimalisasi potensi serta pemenuhan kebutuhan belajar peserta didik.</p>
      </div>
      <div class="px-6 py-5 border-t" style="border-color:rgba(142,119,171,.2);background:#FAFAFA;">
        <label class="flex items-start gap-3 cursor-pointer mb-5">
          <input type="checkbox" id="consentCheck" class="mt-0.5 rounded" style="accent-color:#8E77AB;width:18px;height:18px;flex-shrink:0;">
          <span class="text-sm font-medium" style="color:#2E2046;">Saya Menyetujui Ketentuan di Atas dan Bersedia Melanjutkan.</span>
        </label>
        <div class="flex gap-3">
          <a href="{{ route('member.dashboard') }}" class="flex-1 text-center px-4 py-3 rounded-xl text-sm font-medium" style="border:1px solid rgba(142,119,171,.3);color:#6B7280;">Kembali ke Dashboard</a>
          <button id="consentAcceptBtn" disabled onclick="acceptConsent()"
                  class="flex-1 px-4 py-3 rounded-xl text-sm font-semibold transition-all opacity-40 cursor-not-allowed"
                  style="background:linear-gradient(135deg,#4A3769,#8E77AB);color:#fff;">
            Lanjutkan Asesmen
          </button>
        </div>
      </div>
    </div>
  </div>

  {{-- Multi-child selector --}}
  @if($children->count() > 1)
    <div class="mb-5">
      <h3 class="text-sm font-semibold text-[#2E2046] mb-3">Pilih Anak yang Akan Diases</h3>
      <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
        @foreach($children as $c)
          <label class="child-select-card cursor-pointer block">
            <input type="radio" name="selected_child_ui" value="{{ $c->id }}" class="sr-only child-radio" {{ $loop->first ? 'checked' : '' }}>
            <div class="child-card-inner p-4 rounded-xl border-2 transition-all flex items-center gap-3"
                 style="border-color:{{ $loop->first ? '#8E77AB' : 'rgba(186,166,214,.3)' }};background:{{ $loop->first ? '#fff' : '#F5F5F6' }}">
              <div class="w-10 h-10 rounded-xl flex items-center justify-center text-white font-bold flex-shrink-0" style="background:linear-gradient(135deg,#4A3769,#8E77AB)">
                {{ strtoupper(substr($c->full_name, 0, 1)) }}
              </div>
              <div class="flex-1 min-w-0">
                <p class="text-sm font-semibold text-[#2E2046] truncate">{{ $c->full_name }}</p>
                <p class="text-xs text-gray-500">{{ $c->school_name ?? '—' }}{{ $c->birth_date ? ' · '.(\Carbon\Carbon::parse($c->birth_date)->age).' thn' : '' }}</p>
              </div>
              <div class="w-5 h-5 rounded-full border-2 flex-shrink-0 flex items-center justify-center" style="border-color:#8E77AB">
                <div class="w-2.5 h-2.5 rounded-full {{ $loop->first ? '' : 'hidden' }} child-dot" style="background:#8E77AB"></div>
              </div>
            </div>
          </label>
        @endforeach
      </div>
    </div>
  @else
    {{-- Single child strip --}}
    <div class="flex items-center gap-4 mb-6 px-5 py-4 rounded-2xl" style="background:#fff;border:1px solid rgba(186,166,214,.2)">
      <div class="w-11 h-11 rounded-xl flex items-center justify-center text-white font-bold text-lg flex-shrink-0" style="background:linear-gradient(135deg,#4A3769,#8E77AB)">
        {{ strtoupper(substr($child->full_name, 0, 1)) }}
      </div>
      <div class="flex-1">
        <p class="text-sm font-semibold text-[#2E2046]">{{ $child->full_name }}</p>
        <p class="text-xs text-gray-500">
          {{ $child->school_name ?? 'Sekolah belum diisi' }}
          @if($child->birth_date) · {{ \Carbon\Carbon::parse($child->birth_date)->age }} tahun @endif
        </p>
      </div>
      <a href="{{ route('member.children.edit', $child) }}" class="text-xs text-[#4A3769] hover:underline flex-shrink-0">
        <i class="fas fa-edit mr-1"></i>Edit
      </a>
    </div>
  @endif

  {{-- Category selection form --}}
  <form action="{{ route('member.assessment.begin') }}" method="POST" id="assessmentForm">
    @csrf
    <input type="hidden" name="child_id" id="childIdInput" value="{{ $child->id }}">

    <div class="dash-card">
      <div class="mb-5">
        <h2 class="text-lg font-semibold text-[#2E2046] mb-1" style="font-family:'Playfair Display',serif">Pilih Kategori Asesmen</h2>
        <p class="text-sm text-gray-500">Pilih jenis hambatan yang ingin diidentifikasi.</p>
      </div>

      @php
        $iconMap = [
          'brain'=>'fa-brain','eye'=>'fa-eye','book'=>'fa-book-open','clock'=>'fa-clock',
          'dna'=>'fa-dna','accessibility'=>'fa-wheelchair','ear'=>'fa-assistive-listening-systems',
          'message'=>'fa-comment-medical','puzzle'=>'fa-puzzle-piece','zap'=>'fa-bolt',
          'heart'=>'fa-heart-pulse','layers'=>'fa-layer-group','pencil'=>'fa-pen-nib',
          'calculator'=>'fa-calculator',
        ];
      @endphp
      @if($categories->isNotEmpty())
        <div class="mb-6 space-y-5">
          @foreach($groupedCategories as $groupKey => $groupCats)
            <div>
              <div class="flex items-center gap-2 mb-2">
                <div class="h-px flex-1" style="background:rgba(186,166,214,.25)"></div>
                <span class="text-xs font-bold uppercase tracking-wider px-2" style="color:#8499B6;white-space:nowrap;">
                  {{ $groupLabels[$groupKey] ?? ucwords(str_replace('_',' ',$groupKey)) }}
                </span>
                <div class="h-px flex-1" style="background:rgba(186,166,214,.25)"></div>
              </div>
              <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                @foreach($groupCats as $category)
                  <label class="category-card cursor-pointer block">
                    <input type="radio" name="category_id" value="{{ $category->id }}" class="sr-only category-radio">
                    <div class="category-card-inner p-3 rounded-xl border-2 transition-all h-full" style="border-color:rgba(186,166,214,.3);background:#F5F5F6">
                      <div class="flex items-start gap-3">
                        <div class="w-9 h-9 rounded-xl flex items-center justify-center flex-shrink-0 mt-0.5" style="background:{{ $category->color ?? '#8E77AB' }}20">
                          <i class="fas {{ $iconMap[$category->icon] ?? 'fa-clipboard-list' }}" style="font-size:.85rem;color:{{ $category->color ?? '#8E77AB' }}"></i>
                        </div>
                        <div class="min-w-0">
                          <p class="text-sm font-semibold text-[#2E2046] leading-tight">{{ $category->name }}</p>
                          @if($category->description)
                            <p class="text-xs text-gray-400 mt-0.5 leading-relaxed">{{ Str::limit($category->description, 75) }}</p>
                          @endif
                        </div>
                      </div>
                    </div>
                  </label>
                @endforeach
              </div>
            </div>
          @endforeach
        </div>
        <div class="flex justify-end">
          <button type="submit" id="submitBtn" disabled
                  class="btn-primary text-base px-8 py-3 opacity-40 cursor-not-allowed" style="transition:all .2s">
            <i class="fas fa-arrow-right"></i> Mulai Asesmen
          </button>
        </div>
      @else
        <div class="text-center py-10 rounded-xl" style="background:#F0EEF5">
          <p class="text-sm text-gray-500">Belum ada kategori asesmen yang tersedia.</p>
        </div>
      @endif
    </div>
  </form>
@endif

@push('styles')
<style>
  .category-card-inner.selected { border-color:#8E77AB !important; background:#fff !important; box-shadow:0 0 0 3px rgba(142,119,171,.15); }
</style>
@endpush

@push('scripts')
<script>
  var consentAccepted = false;

  document.getElementById('consentCheck')?.addEventListener('change', function() {
    var btn = document.getElementById('consentAcceptBtn');
    if (!btn) return;
    btn.disabled = !this.checked;
    btn.classList.toggle('opacity-40', !this.checked);
    btn.classList.toggle('cursor-not-allowed', !this.checked);
  });

  function acceptConsent() {
    consentAccepted = true;
    document.getElementById('consentModal').style.display = 'none';
  }

  // Multi-child selection
  document.querySelectorAll('.child-radio').forEach(function(radio) {
    radio.addEventListener('change', function() {
      document.querySelectorAll('.child-card-inner').forEach(function(el) {
        el.style.borderColor = 'rgba(186,166,214,.3)';
        el.style.background = '#F5F5F6';
        el.querySelector('.child-dot')?.classList.add('hidden');
      });
      if (this.checked) {
        var inner = this.closest('.child-select-card').querySelector('.child-card-inner');
        inner.style.borderColor = '#8E77AB';
        inner.style.background = '#fff';
        inner.querySelector('.child-dot')?.classList.remove('hidden');
        document.getElementById('childIdInput').value = this.value;
      }
    });
  });

  // Category selection
  document.querySelectorAll('.category-radio').forEach(function(radio) {
    radio.addEventListener('change', function() {
      document.querySelectorAll('.category-card-inner').forEach(function(el) { el.classList.remove('selected'); });
      if (this.checked) {
        this.closest('.category-card').querySelector('.category-card-inner').classList.add('selected');
        var btn = document.getElementById('submitBtn');
        if (btn) { btn.disabled = false; btn.classList.remove('opacity-40','cursor-not-allowed'); }
      }
    });
  });

  // Block submit until consent
  document.getElementById('assessmentForm')?.addEventListener('submit', function(e) {
    if (!consentAccepted) {
      e.preventDefault();
      document.getElementById('consentModal').style.display = 'flex';
    }
  });
</script>
@endpush

@endsection
