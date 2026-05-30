@extends('member.layout.master')
@section('title', 'Tambah Data Anak')
@section('page-title', 'Tambah Data Anak')

@section('content')

{{-- Validation errors --}}
@if($errors->any())
  <div class="mb-6 p-4 rounded-xl text-sm" style="background:#fee2e2;color:#991b1b;border:1px solid #fecaca">
    <div class="font-semibold mb-2 flex items-center gap-2"><i class="fas fa-exclamation-circle"></i> Periksa kembali isian berikut:</div>
    <ul class="list-disc list-inside space-y-1">
      @foreach($errors->all() as $error)
        <li>{{ $error }}</li>
      @endforeach
    </ul>
  </div>
@endif

<div class="max-w-2xl mx-auto">
  <div class="dash-card">
    <div class="mb-6">
      <h2 class="text-xl font-semibold text-[#2E2046]" style="font-family:'Playfair Display',serif">Data Anak</h2>
      <p class="text-sm text-gray-500 mt-1">Isi informasi anak yang akan dilakukan asesmen identifikasi</p>
    </div>

    <form action="{{ route('member.children.store') }}" method="POST" enctype="multipart/form-data">
      @csrf

      <div class="space-y-5">
        {{-- Full name & nickname --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
          <div>
            <label class="block text-xs font-semibold text-[#4A3769] mb-1.5">Nama Lengkap <span class="text-red-500">*</span></label>
            <input type="text" name="full_name" value="{{ old('full_name') }}" class="form-input" placeholder="Nama lengkap anak" required>
          </div>
          <div>
            <label class="block text-xs font-semibold text-[#4A3769] mb-1.5">Nama Panggilan</label>
            <input type="text" name="nick_name" value="{{ old('nick_name') }}" class="form-input" placeholder="Nama panggilan">
          </div>
        </div>

        {{-- Gender & birth date --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
          <div>
            <label class="block text-xs font-semibold text-[#4A3769] mb-1.5">Jenis Kelamin <span class="text-red-500">*</span></label>
            <select name="gender" class="form-input" required>
              <option value="">— Pilih —</option>
              <option value="male" {{ old('gender') === 'male' ? 'selected' : '' }}>Laki-laki</option>
              <option value="female" {{ old('gender') === 'female' ? 'selected' : '' }}>Perempuan</option>
            </select>
          </div>
          <div>
            <label class="block text-xs font-semibold text-[#4A3769] mb-1.5">Tanggal Lahir <span class="text-red-500">*</span></label>
            <input type="date" name="birth_date" value="{{ old('birth_date') }}" class="form-input" required>
          </div>
        </div>

        {{-- Birth place & parent name --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
          <div>
            <label class="block text-xs font-semibold text-[#4A3769] mb-1.5">Tempat Lahir</label>
            <input type="text" name="birth_place" value="{{ old('birth_place') }}" class="form-input" placeholder="Kota kelahiran">
          </div>
          <div>
            <label class="block text-xs font-semibold text-[#4A3769] mb-1.5">Nama Orang Tua / Wali</label>
            <input type="text" name="parent_name" value="{{ old('parent_name') }}" class="form-input" placeholder="Nama orang tua atau wali">
          </div>
        </div>

        {{-- Parent phone --}}
        <div>
          <label class="block text-xs font-semibold text-[#4A3769] mb-1.5">No. HP Orang Tua / Wali</label>
          <input type="text" name="parent_phone" value="{{ old('parent_phone') }}" class="form-input" placeholder="Contoh: 08123456789">
        </div>

        {{-- School & class --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
          <div>
            <label class="block text-xs font-semibold text-[#4A3769] mb-1.5">Nama Sekolah</label>
            <input type="text" name="school_name" value="{{ old('school_name') }}" class="form-input" placeholder="Nama sekolah">
          </div>
          <div>
            <label class="block text-xs font-semibold text-[#4A3769] mb-1.5">Kelas</label>
            <input type="text" name="class_name" value="{{ old('class_name') }}" class="form-input" placeholder="Contoh: 3A">
          </div>
        </div>

        {{-- Address --}}
        <div>
          <label class="block text-xs font-semibold text-[#4A3769] mb-1.5">Alamat</label>
          <textarea name="address" rows="2" class="form-input" placeholder="Alamat lengkap">{{ old('address') }}</textarea>
        </div>

        {{-- Notes --}}
        <div>
          <label class="block text-xs font-semibold text-[#4A3769] mb-1.5">Catatan Tambahan</label>
          <textarea name="notes" rows="3" class="form-input" placeholder="Catatan khusus mengenai anak (opsional)">{{ old('notes') }}</textarea>
        </div>

        {{-- Photo --}}
        <div>
          <label class="block text-xs font-semibold text-[#4A3769] mb-1.5">Foto Anak</label>
          <input type="file" name="photo" accept="image/*" class="form-input">
          <p class="text-xs text-gray-400 mt-1">Format: JPG, PNG. Maks 2MB</p>
        </div>
      </div>

      {{-- Buttons --}}
      <div class="flex items-center gap-3 mt-8 pt-6 border-t" style="border-color:rgba(186,166,214,.2)">
        <button type="submit" class="btn-primary">
          <i class="fas fa-save"></i> Simpan Data
        </button>
        <a href="{{ route('member.children.index') }}" class="btn-secondary">
          Batal
        </a>
      </div>
    </form>
  </div>
</div>

@endsection