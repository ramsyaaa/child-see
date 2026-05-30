@extends('home.layout.master')
@section('title', 'Instructors — InkluSyncID')

@section('content')

{{-- HERO --}}
<section class="py-16 px-6 text-center reveal">
  <div class="max-w-2xl mx-auto">
    <h1 class="text-5xl font-semibold mb-4" style="font-family:'Playfair Display',serif">Meet Our Instructors</h1>
    <p class="text-[#7a7a7a] text-sm leading-relaxed">Experienced, compassionate teachers dedicated to guiding you on your wellness journey</p>
  </div>
</section>

{{-- INSTRUCTORS GRID --}}
<section class="px-6 pb-16">
  <div class="max-w-5xl mx-auto grid grid-cols-1 md:grid-cols-2 gap-8">
    @forelse($instructors as $idx => $instructor)
      <div class="reveal group" @if($idx % 2 !== 0) style="transition-delay:0.1s" @endif>
        <div class="rounded-2xl overflow-hidden h-72 md:h-80 mb-5 relative">
          @if($instructor->photo_url)
            <img src="{{ asset('storage/'.$instructor->photo_url) }}"
                 class="w-full h-full object-cover object-top grayscale group-hover:grayscale-0 transition-all duration-500 group-hover:scale-105"
                 alt="{{ $instructor->name }}">
          @else
            @php
              $defaultPhotos = [
                'https://images.unsplash.com/photo-1544005313-94ddf0286df2?auto=format&fit=crop&q=80&w=800',
                'https://images.unsplash.com/photo-1607746882042-944635dfe10e?auto=format&fit=crop&q=80&w=800',
                'https://images.unsplash.com/photo-1571019614242-c5c5dee9f50b?auto=format&fit=crop&q=80&w=800',
                'https://images.unsplash.com/photo-1593811167562-9cef47bfc4d7?auto=format&fit=crop&q=80&w=800',
              ];
            @endphp
            <img src="{{ $defaultPhotos[$idx % 4] }}"
                 class="w-full h-full object-cover object-top grayscale group-hover:grayscale-0 transition-all duration-500 group-hover:scale-105"
                 alt="{{ $instructor->name }}">
          @endif
        </div>
        <h3 class="text-2xl font-semibold mb-1" style="font-family:'Playfair Display',serif">{{ $instructor->name }}</h3>
        <p class="text-[#C4923A] text-sm font-medium mb-3">{{ $instructor->specialization }}</p>
        <p class="text-[#7a7a7a] text-sm leading-relaxed mb-4">{{ $instructor->bio }}</p>
        <div class="flex gap-4 text-sm text-[#7a7a7a]">
          <a href="{{ route('classes') }}" class="flex items-center gap-1.5 hover:text-[#B85C38] transition-colors">
            <i class="fas fa-calendar-alt text-[#C4923A]"></i> Browse Schedule
          </a>
        </div>
      </div>
    @empty
      @foreach([['Maya Anindita','Lead Yoga Instructor','500-hour certified yoga instructor specializing in Vinyasa and for the Yoga Method for Creating Space for authentic self-expression through mindful movement.','https://images.unsplash.com/photo-1544005313-94ddf0286df2?auto=format&fit=crop&q=80&w=800'],['Sarah Kusuma','Mat Pilates Specialist','Certified Pilates instructor with a compassionate nature. Sarah\'s classes focus on building strength with refinement and self-awareness through intentional movement.','https://images.unsplash.com/photo-1607746882042-944635dfe10e?auto=format&fit=crop&q=80&w=800'],['Devi Lakshmi','Meditation & Sound Healing','Experienced meditation teacher and sound healer. Devi guides students through breathwork, body scan meditation, and healing sound practices.','https://images.unsplash.com/photo-1571019614242-c5c5dee9f50b?auto=format&fit=crop&q=80&w=800'],['Rina Permata','Dance Fitness Instructor','Professional dancer and choreographer who brings energy, joy, and liberation to every class. Rina makes fitness into a dance.','https://images.unsplash.com/photo-1593811167562-9cef47bfc4d7?auto=format&fit=crop&q=80&w=800']] as $idx => $inst)
        <div class="reveal group" @if($idx % 2 !== 0) style="transition-delay:0.1s" @endif>
          <div class="rounded-2xl overflow-hidden h-72 md:h-80 mb-5">
            <img src="{{ $inst[3] }}" class="w-full h-full object-cover object-top grayscale group-hover:grayscale-0 transition-all duration-500 group-hover:scale-105" alt="{{ $inst[0] }}">
          </div>
          <h3 class="text-2xl font-semibold mb-1" style="font-family:'Playfair Display',serif">{{ $inst[0] }}</h3>
          <p class="text-[#C4923A] text-sm font-medium mb-3">{{ $inst[1] }}</p>
          <p class="text-[#7a7a7a] text-sm leading-relaxed mb-4">{{ $inst[2] }}</p>
          <div class="flex gap-4 text-sm text-[#7a7a7a]">
            <a href="{{ route('classes') }}" class="flex items-center gap-1.5 hover:text-[#B85C38] transition-colors"><i class="fas fa-calendar-alt text-[#C4923A]"></i> Browse Schedule</a>
          </div>
        </div>
      @endforeach
    @endforelse
  </div>
</section>

{{-- JOIN BANNER --}}
<section class="py-20 px-6 bg-[#f5f0e8] border-t border-[#e8e0d0]">
  <div class="max-w-2xl mx-auto text-center reveal">
    <h2 class="text-3xl font-semibold mb-3" style="font-family:'Playfair Display',serif">Interested in Teaching With Us?</h2>
    <p class="text-[#7a7a7a] text-sm leading-relaxed mb-8">We are always looking for passionate, experienced instructors who share our values of intentionality, community, and authentic growth.</p>
    <a href="mailto:hello@inklusyncid.id" class="inline-block bg-[#C4923A] text-white px-8 py-3 rounded-full text-sm font-medium hover:bg-[#A07830] transition-colors">Get In Touch</a>
  </div>
</section>

@endsection
