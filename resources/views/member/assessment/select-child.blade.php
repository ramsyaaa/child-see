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

@if(!$child)
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

  {{-- Child info strip --}}
  <div class="flex items-center gap-4 mb-6 px-5 py-4 rounded-2xl" style="background:#fff;border:1px solid rgba(186,166,214,.2)">
    <div class="w-11 h-11 rounded-xl flex items-center justify-center text-white font-bold text-lg flex-shrink-0" style="background:linear-gradient(135deg,#4A3769,#BAA6D6)">
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

  {{-- Category selection --}}
  <form action="{{ route('member.assessment.begin') }}" method="POST" id="assessmentForm">
    @csrf

    <div class="dash-card">
      <div class="mb-5">
        <h2 class="text-lg font-semibold text-[#2E2046] mb-1" style="font-family:'Playfair Display',serif">Pilih Kategori Asesmen</h2>
        <p class="text-sm text-gray-500">Pilih jenis hambatan yang ingin diidentifikasi pada <strong>{{ $child->full_name }}</strong>.</p>
      </div>

      @if($categories->isNotEmpty())
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mb-6">
          @foreach($categories as $category)
            <label class="category-card cursor-pointer block">
              <input type="radio" name="category_id" value="{{ $category->id }}" class="sr-only category-radio">
              <div class="category-card-inner p-4 rounded-xl border-2 transition-all h-full" style="border-color:rgba(186,166,214,.3);background:#F5F5F6">
                <div class="flex items-start gap-3">
                  <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0 mt-0.5" style="background:{{ $category->color ?? '#4A3769' }}20">
                    @php
                      $icons = ['brain'=>'fa-brain','eye'=>'fa-eye','book'=>'fa-book-open','clock'=>'fa-clock'];
                      $ico = $icons[$category->icon] ?? 'fa-clipboard-list';
                    @endphp
                    <i class="fas {{ $ico }}" style="color:{{ $category->color ?? '#4A3769' }}"></i>
                  </div>
                  <div>
                    <p class="text-sm font-semibold text-[#2E2046]">{{ $category->name }}</p>
                    @if($category->description)
                      <p class="text-xs text-gray-500 mt-1 leading-relaxed">{{ Str::limit($category->description, 90) }}</p>
                    @endif
                  </div>
                </div>
              </div>
            </label>
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
  .category-card-inner.selected { border-color: #4A3769 !important; background: #fff !important; box-shadow: 0 0 0 3px rgba(74,55,105,.12); }
</style>
@endpush

@push('scripts')
<script>
  document.querySelectorAll('.category-radio').forEach(function(radio) {
    radio.addEventListener('change', function() {
      document.querySelectorAll('.category-card-inner').forEach(function(el) {
        el.classList.remove('selected');
      });
      if (this.checked) {
        this.closest('.category-card').querySelector('.category-card-inner').classList.add('selected');
        var btn = document.getElementById('submitBtn');
        if (btn) {
          btn.disabled = false;
          btn.classList.remove('opacity-40', 'cursor-not-allowed');
        }
      }
    });
  });
</script>
@endpush

@endsection
