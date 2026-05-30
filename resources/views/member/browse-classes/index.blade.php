@extends('member.layout.master')
@section('title', 'Browse Classes')
@section('page-title', 'Browse Classes')

@section('content')

<div class="mb-6">
  <h1 class="text-xl font-semibold text-[#1a1a1a]" style="font-family:'Playfair Display',serif">Browse Classes</h1>
  <p class="text-xs text-[#7a7a7a] mt-0.5">Explore our class offerings</p>
</div>

{{-- Filters --}}
<div class="dash-card mb-5">
  <form method="GET" action="{{ route('member.browse-classes.index') }}" class="flex flex-wrap gap-3 items-end">
    <div class="flex-1 min-w-[160px]">
      <label class="block text-xs font-semibold text-[#4a4a4a] mb-1.5">Search</label>
      <input type="text" name="search" class="form-input" placeholder="Search classes..." value="{{ request('search') }}">
    </div>
    <div class="flex-1 min-w-[140px]">
      <label class="block text-xs font-semibold text-[#4a4a4a] mb-1.5">Category</label>
      <select name="category" class="form-input">
        <option value="">All Categories</option>
        @foreach($categories as $category)
          <option value="{{ $category }}" {{ request('category') == $category ? 'selected' : '' }}>
            {{ $category }}
          </option>
        @endforeach
      </select>
    </div>
    <div class="flex-1 min-w-[140px]">
      <label class="block text-xs font-semibold text-[#4a4a4a] mb-1.5">Level</label>
      <select name="difficulty" class="form-input">
        <option value="">All Levels</option>
        <option value="beginner" {{ request('difficulty') == 'beginner' ? 'selected' : '' }}>Beginner</option>
        <option value="intermediate" {{ request('difficulty') == 'intermediate' ? 'selected' : '' }}>Intermediate</option>
        <option value="advanced" {{ request('difficulty') == 'advanced' ? 'selected' : '' }}>Advanced</option>
      </select>
    </div>
    <div class="flex gap-2">
      <button type="submit" class="btn-grad px-5 py-2.5 text-xs">
        <i class="fas fa-search mr-1"></i> Search
      </button>
      @if(request()->anyFilled(['search','category','difficulty']))
        <a href="{{ route('member.browse-classes.index') }}"
           class="px-4 py-2.5 rounded-full text-xs border border-[#e8e0d0] text-[#7a7a7a] hover:text-[#B85C38] hover:border-[#B85C38] transition-colors">
          Clear
        </a>
      @endif
    </div>
  </form>
</div>

@if($masterClasses->count())
  <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
    @foreach($masterClasses as $class)
      <div class="dash-card flex flex-col">
        {{-- Color accent bar --}}
        <div class="h-1 rounded-full mb-4 -mt-1" style="background:{{ $class->color ?? '#C4923A' }}"></div>

        <div class="flex items-start justify-between mb-2">
          <h3 class="font-semibold text-[#1a1a1a] text-sm flex-1 pr-2" style="font-family:'Playfair Display',serif">
            {{ $class->name }}
          </h3>
          <span class="flex-shrink-0 text-[10px] px-2 py-0.5 rounded-full font-semibold
            {{ $class->difficulty_level == 'beginner' ? 'bg-green-100 text-green-700'
               : ($class->difficulty_level == 'intermediate' ? 'bg-amber-100 text-amber-700'
               : 'bg-red-100 text-red-700') }}">
            {{ ucfirst($class->difficulty_level) }}
          </span>
        </div>

        <p class="text-xs text-[#7a7a7a] mb-3">
          <i class="fas fa-tag mr-1 text-[#C4923A]"></i>{{ $class->category }}
          <span class="mx-1.5 text-[#e8e0d0]">·</span>
          <i class="fas fa-clock mr-1 text-[#C4923A]"></i>{{ $class->default_duration }} min
        </p>

        <p class="text-xs text-[#4a4a4a] leading-relaxed flex-1 mb-4">
          {{ Str::limit($class->description, 100) }}
        </p>

        <a href="{{ route('member.browse-classes.show', $class) }}"
           class="mt-auto block text-center btn-grad text-xs py-2.5 rounded-full">
          View Details <i class="fas fa-arrow-right ml-1"></i>
        </a>
      </div>
    @endforeach
  </div>

  @if($masterClasses->hasPages())
    <div class="mt-4">{{ $masterClasses->links() }}</div>
  @endif

@else
  <div class="dash-card text-center py-16">
    <div class="w-14 h-14 bg-[#f5f0e8] rounded-full flex items-center justify-center mx-auto mb-3">
      <i class="fas fa-search text-[#C4923A] text-xl"></i>
    </div>
    <p class="text-sm text-[#7a7a7a]">No classes found matching your filters</p>
  </div>
@endif

@endsection
