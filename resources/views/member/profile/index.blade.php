@extends('member.layout.master')
@section('title', 'My Profile')
@section('page-title', 'My Profile')

@section('content')

<div class="mb-6">
  <h1 class="text-xl font-semibold text-[#1a1a1a]" style="font-family:'Playfair Display',serif">My Profile</h1>
  <p class="text-xs text-[#7a7a7a] mt-0.5">Manage your account information</p>
</div>

@if(session('success'))
  <div class="mb-4 flex items-start gap-3 bg-green-50 border border-green-200 text-green-800 text-sm px-4 py-3 rounded-xl">
    <i class="fas fa-check-circle mt-0.5 flex-shrink-0"></i>
    <span>{{ session('success') }}</span>
  </div>
@endif
@if(session('error'))
  <div class="mb-4 flex items-start gap-3 bg-red-50 border border-red-200 text-red-800 text-sm px-4 py-3 rounded-xl">
    <i class="fas fa-exclamation-circle mt-0.5 flex-shrink-0"></i>
    <span>{{ session('error') }}</span>
  </div>
@endif

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

  {{-- Profile info --}}
  <div class="dash-card">
    <h2 class="font-semibold text-[#1a1a1a] mb-5 text-sm" style="font-family:'Playfair Display',serif">Profile Information</h2>
    <form action="{{ route('member.profile.update') }}" method="POST" class="space-y-4">
      @csrf
      @method('PUT')

      <div>
        <label class="block text-xs font-semibold text-[#4a4a4a] mb-1.5">
          Full Name <span class="text-red-400">*</span>
        </label>
        <input type="text" name="name" required
          class="form-input @error('name') border-red-400 @enderror"
          value="{{ old('name', auth()->user()->name) }}">
        @error('name')
          <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
        @enderror
      </div>

      <div>
        <label class="block text-xs font-semibold text-[#4a4a4a] mb-1.5">
          Email <span class="text-red-400">*</span>
        </label>
        <input type="email" name="email" required
          class="form-input @error('email') border-red-400 @enderror"
          value="{{ old('email', auth()->user()->email) }}">
        @error('email')
          <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
        @enderror
      </div>

      <div>
        <label class="block text-xs font-semibold text-[#4a4a4a] mb-1.5">
          Username <span class="text-red-400">*</span>
        </label>
        <input type="text" name="username" required
          class="form-input @error('username') border-red-400 @enderror"
          value="{{ old('username', auth()->user()->username) }}">
        @error('username')
          <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
        @enderror
      </div>

      <div>
        <label class="block text-xs font-semibold text-[#4a4a4a] mb-1.5">Phone</label>
        <input type="text" name="phone"
          class="form-input @error('phone') border-red-400 @enderror"
          value="{{ old('phone', auth()->user()->phone) }}">
        @error('phone')
          <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
        @enderror
      </div>

      <button type="submit" class="btn-grad w-full mt-2">
        <i class="fas fa-save mr-1.5"></i> Save Changes
      </button>
    </form>
  </div>

  {{-- Change password --}}
  <div class="dash-card">
    <h2 class="font-semibold text-[#1a1a1a] mb-5 text-sm" style="font-family:'Playfair Display',serif">Change Password</h2>
    <form action="{{ route('member.profile.password') }}" method="POST" class="space-y-4">
      @csrf

      <div>
        <label class="block text-xs font-semibold text-[#4a4a4a] mb-1.5">
          Current Password <span class="text-red-400">*</span>
        </label>
        <input type="password" name="current_password" required
          class="form-input @error('current_password') border-red-400 @enderror">
        @error('current_password')
          <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
        @enderror
      </div>

      <div>
        <label class="block text-xs font-semibold text-[#4a4a4a] mb-1.5">
          New Password <span class="text-red-400">*</span>
        </label>
        <input type="password" name="new_password" required
          class="form-input @error('new_password') border-red-400 @enderror">
        @error('new_password')
          <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
        @enderror
      </div>

      <div>
        <label class="block text-xs font-semibold text-[#4a4a4a] mb-1.5">
          Confirm New Password <span class="text-red-400">*</span>
        </label>
        <input type="password" name="new_password_confirmation" required class="form-input">
      </div>

      <button type="submit"
        class="w-full mt-2 py-2.5 rounded-full text-sm font-semibold border-2 border-[#C4923A] text-[#C4923A] hover:bg-[#C4923A] hover:text-white transition-all">
        <i class="fas fa-key mr-1.5"></i> Update Password
      </button>
    </form>
  </div>

</div>

@endsection
