@extends('home.layout.master')

@section('title', ($site['seo_title'] ?? '') ?: 'Child See — Identifikasi Anak Berkebutuhan Khusus')
@section('description', ($site['seo_description'] ?? '') ?: 'Child See membantu guru dan orang tua mengidentifikasi hambatan belajar anak SD secara dini.')

@section('content')

{{-- ── HERO ─────────────────────────────────────────────────────────── --}}
<section style="background:linear-gradient(145deg,#4A3769 0%,#6B5A8E 50%,#8E77AB 100%);padding:6rem 0 5rem;position:relative;overflow:hidden;">
    <div style="position:absolute;inset:0;background-image:radial-gradient(circle,rgba(198,217,232,.06) 1px,transparent 1px);background-size:28px 28px;"></div>
    <div style="position:absolute;top:0;right:0;width:520px;height:520px;border-radius:50%;background:radial-gradient(circle at 70% 30%,#C6D9E8,transparent 65%);opacity:.1;transform:translate(25%,-25%);"></div>
    <div class="max-w-7xl mx-auto px-6 lg:px-10" style="position:relative;z-index:1;">
        <div style="max-width:42rem;">
            <div style="display:inline-flex;align-items:center;gap:.5rem;padding:.35rem .9rem;border-radius:999px;margin-bottom:1.5rem;background:rgba(198,217,232,.12);border:1px solid rgba(198,217,232,.28);">
                <span style="width:.45rem;height:.45rem;border-radius:50%;background:#C6D9E8;display:block;"></span>
                <span style="font-size:.68rem;text-transform:uppercase;letter-spacing:.15em;font-weight:500;color:#C6D9E8;">Identifikasi ABK Terstandar</span>
            </div>
            <h1 class="font-display font-bold" style="font-size:clamp(2rem,5vw,3.25rem);color:#FFFFFF;line-height:1.2;margin-bottom:1.25rem;">
                Kenali Potensi<br>
                <em class="not-italic" style="color:#C6D9E8;">Setiap Anak</em><br>
                Lebih Awal
            </h1>
            <p style="font-size:clamp(.9rem,2vw,1.05rem);color:rgba(255,255,255,0.68);max-width:480px;line-height:1.65;margin-bottom:2rem;">
                Child See membantu guru dan orang tua mengidentifikasi hambatan belajar anak SD secara dini dengan instrumen observasi terstandar dan berbasis bukti.
            </p>
            <div style="display:flex;flex-wrap:wrap;gap:.75rem;">
                @auth
                    <a href="{{ route('member.assessment.start') }}" style="display:inline-flex;align-items:center;gap:.5rem;padding:.75rem 1.5rem;border-radius:.85rem;font-weight:600;font-size:.93rem;background:#ffffff;color:#4A3769;text-decoration:none;">Mulai Asesmen Sekarang</a>
                    <a href="{{ route('member.dashboard') }}" style="display:inline-flex;align-items:center;padding:.75rem 1.5rem;border-radius:.85rem;font-weight:500;font-size:.93rem;border:1.5px solid rgba(198,217,232,.4);color:rgba(255,255,255,.88);text-decoration:none;">Dashboard Saya</a>
                @else
                    <a href="{{ route('register') }}" style="display:inline-flex;align-items:center;gap:.5rem;padding:.75rem 1.5rem;border-radius:.85rem;font-weight:600;font-size:.93rem;background:#ffffff;color:#4A3769;text-decoration:none;">Daftar Gratis &rarr;</a>
                    <a href="{{ route('login') }}" style="display:inline-flex;align-items:center;padding:.75rem 1.5rem;border-radius:.85rem;font-weight:500;font-size:.93rem;border:1.5px solid rgba(198,217,232,.4);color:rgba(255,255,255,.88);text-decoration:none;">Sudah Punya Akun</a>
                @endauth
            </div>
        </div>
        <div style="display:grid;grid-template-columns:repeat(3,auto);gap:1.5rem;margin-top:3.5rem;max-width:340px;">
            <div style="text-align:center;"><div class="font-display font-bold" style="font-size:1.75rem;color:#C6D9E8;">4</div><div style="font-size:.62rem;margin-top:.2rem;text-transform:uppercase;letter-spacing:.1em;color:rgba(255,255,255,.4);">Kategori</div></div>
            <div style="text-align:center;border-left:1px solid rgba(198,217,232,.2);border-right:1px solid rgba(198,217,232,.2);padding:0 1rem;"><div class="font-display font-bold" style="font-size:1.75rem;color:#C6D9E8;">49+</div><div style="font-size:.62rem;margin-top:.2rem;text-transform:uppercase;letter-spacing:.1em;color:rgba(255,255,255,.4);">Indikator</div></div>
            <div style="text-align:center;"><div class="font-display font-bold" style="font-size:1.75rem;color:#C6D9E8;">100%</div><div style="font-size:.62rem;margin-top:.2rem;text-transform:uppercase;letter-spacing:.1em;color:rgba(255,255,255,.4);">Gratis</div></div>
        </div>
    </div>
</section>

{{-- CATEGORIES --}}
<section style="background:#FFFFFF;padding:5rem 0;">
    <div class="max-w-7xl mx-auto px-6 lg:px-10">
        <div style="text-align:center;margin-bottom:3rem;">
            <p style="font-size:.7rem;letter-spacing:.15em;text-transform:uppercase;color:#8499B6;font-family:monospace;margin-bottom:.5rem;">Kategori Identifikasi</p>
            <h2 class="font-display font-bold" style="font-size:clamp(1.5rem,3.5vw,2.2rem);color:#2E2046;margin-bottom:.6rem;">Hambatan yang Dapat Diidentifikasi</h2>
            <p style="color:rgba(46,32,70,.55);font-size:.88rem;max-width:40rem;margin:0 auto;">Instrumen observasi terstandar untuk hambatan belajar utama pada anak usia sekolah dasar.</p>
        </div>
        <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-5">
            @forelse($categories as $cat)
            <div style="background:#F7F5FC;border-radius:1rem;padding:1.4rem;border:1px solid rgba(132,153,182,.12);transition:box-shadow .2s;" onmouseover="this.style.boxShadow='0 4px 20px rgba(74,55,105,.09)'" onmouseout="this.style.boxShadow='none'">
                <div style="width:2.5rem;height:2.5rem;border-radius:.7rem;display:flex;align-items:center;justify-content:center;margin-bottom:.9rem;background:{{ $cat->color ?? '#8499B6' }}18;">
                    @php $svgPaths=['brain'=>'M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 14.5v-9l6 4.5-6 4.5z','eye'=>'M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z M12 9a3 3 0 100 6 3 3 0 000-6z','book'=>'M4 19.5A2.5 2.5 0 016.5 17H20 M4 19.5A2.5 2.5 0 014 17V4h16v13H6.5a2.5 2.5 0 00-2.5 2.5z','clock'=>'M12 2a10 10 0 100 20A10 10 0 0012 2zm0 5v5h5']; @endphp
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="{{ $cat->color ?? '#8499B6' }}" stroke-width="1.8" stroke-linecap="round"><path d="{{ $svgPaths[$cat->icon] ?? $svgPaths['book'] }}"/></svg>
                </div>
                <h3 class="font-display font-bold" style="font-size:.95rem;color:#2E2046;margin-bottom:.4rem;">{{ $cat->name }}</h3>
                <p style="font-size:.81rem;line-height:1.6;color:rgba(46,32,70,.58);">{{ $cat->description }}</p>
                <div style="margin-top:.75rem;"><span style="font-size:.66rem;font-family:monospace;padding:.18rem .5rem;border-radius:.35rem;background:{{ $cat->color ?? '#8499B6' }}14;color:{{ $cat->color ?? '#8499B6' }};">{{ $cat->type }}</span></div>
            </div>
            @empty
            <div style="grid-column:span 4;text-align:center;padding:3rem;color:#9ca3af;">Belum ada kategori tersedia.</div>
            @endforelse
        </div>
    </div>
</section>

{{-- HOW IT WORKS --}}
<section style="background:#F0EDF7;padding:5rem 0;">
    <div class="max-w-7xl mx-auto px-6 lg:px-10">
        <div style="text-align:center;margin-bottom:3rem;">
            <p style="font-size:.7rem;letter-spacing:.15em;text-transform:uppercase;color:#8499B6;font-family:monospace;margin-bottom:.5rem;">Cara Kerja</p>
            <h2 class="font-display font-bold" style="font-size:clamp(1.5rem,3.5vw,2.2rem);color:#2E2046;">Hanya 4 Langkah Mudah</h2>
        </div>
        <div class="grid md:grid-cols-4 gap-8">
            @php $steps=[['n'=>'01','title'=>'Buat Akun','desc'=>'Daftar gratis menggunakan email. Tidak diperlukan kartu kredit.'],['n'=>'02','title'=>'Tambahkan Data Anak','desc'=>'Masukkan profil anak yang ingin diidentifikasi hambatan belajarnya.'],['n'=>'03','title'=>'Isi Kuesioner','desc'=>'Jawab pertanyaan observasi sesuai perilaku anak yang Anda amati sehari-hari.'],['n'=>'04','title'=>'Lihat Hasil','desc'=>'Dapatkan laporan otomatis dengan tingkat indikasi dan rekomendasi tindak lanjut.']]; @endphp
            @foreach($steps as $step)
            <div style="text-align:center;">
                <div style="width:3rem;height:3rem;border-radius:.85rem;margin:0 auto .85rem;display:flex;align-items:center;justify-content:center;background:#B9A5D6;color:#fff;">
                    <span class="font-display font-bold" style="font-size:1rem;">{{ $step['n'] }}</span>
                </div>
                <h3 class="font-display font-bold" style="color:#2E2046;font-size:.93rem;margin-bottom:.4rem;">{{ $step['title'] }}</h3>
                <p style="font-size:.82rem;color:rgba(46,32,70,.58);line-height:1.55;">{{ $step['desc'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ── FAKTA UNIK ────────────────────────────────────────────────────── --}}
@if(isset($facts) && $facts->isNotEmpty())
<section style="background:#F5F5F6;padding:5rem 0;">
    <div style="max-width:1280px;margin:0 auto;padding:0 1.5rem;">
        <div style="margin-bottom:3rem;">
            <p style="font-size:.7rem;letter-spacing:.15em;text-transform:uppercase;color:#8E77AB;font-family:monospace;margin-bottom:.75rem;">Fakta Unik</p>
            <h2 style="font-size:clamp(1.6rem,4vw,2.5rem);font-family:'Playfair Display',serif;font-weight:700;color:#2E2046;line-height:1.25;margin-bottom:.75rem;">Alasan Kita Harus Mulai Mengenali Sedari Dini</h2>
            <p style="color:rgba(38,34,58,.6);font-size:.9rem;max-width:42rem;">Kapan guru dan orang tua harus memulai tes ini? Sekarang — sebelum hambatan semakin dalam dan sulit diatasi.</p>
        </div>
        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(280px,1fr));gap:1.25rem;">
            @foreach($facts as $fact)
            <div style="background:#fff;border-radius:1rem;padding:1.5rem;border:1px solid rgba(142,119,171,.15);transition:box-shadow .2s;" onmouseover="this.style.boxShadow='0 8px 30px rgba(142,119,171,.15)'" onmouseout="this.style.boxShadow='none'">
                <div style="width:2.5rem;height:2.5rem;border-radius:.75rem;background:linear-gradient(135deg,#8E77AB22,#C6D9E855);display:flex;align-items:center;justify-content:center;margin-bottom:1rem;flex-shrink:0;">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#8E77AB" stroke-width="2" stroke-linecap="round"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87L18.18 21 12 17.77 5.82 21 7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                </div>
                <h3 style="font-family:'Playfair Display',serif;font-weight:700;font-size:.95rem;color:#2E2046;margin-bottom:.5rem;line-height:1.4;">{{ $fact->title }}</h3>
                @if($fact->body)<p style="font-size:.82rem;line-height:1.65;color:rgba(38,34,58,.6);margin:0;">{{ $fact->body }}</p>@endif
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- ── TIM PENGEMBANG ─────────────────────────────────────────────────── --}}
@if(isset($teamMembers) && $teamMembers->isNotEmpty())
<section style="background:#fff;padding:5rem 0;">
    <div style="max-width:1280px;margin:0 auto;padding:0 1.5rem;">
        <div style="text-align:center;margin-bottom:3.5rem;">
            <p style="font-size:.7rem;letter-spacing:.15em;text-transform:uppercase;color:#8E77AB;font-family:monospace;margin-bottom:.75rem;">Tim Pengembang</p>
            <h2 style="font-size:clamp(1.6rem,4vw,2.5rem);font-family:'Playfair Display',serif;font-weight:700;color:#2E2046;margin-bottom:.75rem;">Tim di Balik Child See</h2>
            <p style="color:rgba(38,34,58,.6);font-size:.9rem;max-width:38rem;margin:0 auto;">Dikembangkan oleh tim multidisiplin yang berkomitmen pada pendidikan inklusif dan teknologi yang berdampak.</p>
        </div>
        @php
            $dosen     = $teamMembers->where('group', 'dosen');
            $mahasiswa = $teamMembers->where('group', 'mahasiswa');
            $eksternal = $teamMembers->where('group', 'eksternal');
        @endphp
        @if($dosen->isNotEmpty())
        <div style="margin-bottom:3rem;">
            <p style="font-size:.7rem;letter-spacing:.15em;text-transform:uppercase;color:#8499B6;font-family:monospace;text-align:center;margin-bottom:1.5rem;">Dosen Pembimbing</p>
            <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(160px,1fr));gap:2rem;max-width:1000px;margin:0 auto;">
                @foreach($dosen as $m)
                <div style="text-align:center;">
                    <div style="width:8rem;height:8rem;border-radius:1.25rem;margin:0 auto 1rem;display:flex;align-items:center;justify-content:center;overflow:hidden;background:linear-gradient(135deg,#8E77AB18,#C6D9E840);border:2px solid rgba(142,119,171,.2);">
                        @if($m->photo)
                            <img src="{{ asset('storage/'.$m->photo) }}" alt="{{ $m->name }}" style="width:100%;height:100%;object-fit:cover;">
                        @else
                            <span style="font-size:2.5rem;font-weight:700;color:#8E77AB;">{{ strtoupper(substr($m->name,0,1)) }}</span>
                        @endif
                    </div>
                    <p style="font-size:.85rem;font-weight:600;color:#2E2046;line-height:1.3;margin-bottom:.25rem;">{{ $m->name }}</p>
                    @if($m->role_label)<p style="font-size:.72rem;color:#8E77AB;margin:0;">{{ $m->role_label }}</p>@endif
                    @if($m->affiliation)<p style="font-size:.68rem;color:rgba(38,34,58,.4);margin:0;">{{ $m->affiliation }}</p>@endif
                </div>
                @endforeach
            </div>
        </div>
        @endif
        @php $rest = $mahasiswa->merge($eksternal); @endphp
        @if($rest->isNotEmpty())
        <div style="padding-top:1.5rem;border-top:1px solid rgba(142,119,171,.12);">
            <div style="display:flex;flex-wrap:wrap;justify-content:center;gap:2rem;">
                @foreach($rest as $m)
                <div style="text-align:center;width:160px;">
                    <div style="width:8rem;height:8rem;border-radius:1.25rem;margin:0 auto 1rem;display:flex;align-items:center;justify-content:center;overflow:hidden;background:linear-gradient(135deg,#8499B618,#C6D9E840);border:2px solid rgba(132,153,182,.2);">
                        @if($m->photo)
                            <img src="{{ asset('storage/'.$m->photo) }}" alt="{{ $m->name }}" style="width:100%;height:100%;object-fit:cover;">
                        @else
                            <span style="font-size:2.5rem;font-weight:700;color:#8499B6;">{{ strtoupper(substr($m->name,0,1)) }}</span>
                        @endif
                    </div>
                    <p style="font-size:.85rem;font-weight:600;color:#2E2046;line-height:1.3;margin-bottom:.25rem;">{{ $m->name }}</p>
                    @if($m->role_label)<p style="font-size:.72rem;color:#8499B6;margin-bottom:.25rem;">{{ $m->role_label }}</p>@endif
                    <span style="font-size:.65rem;padding:.15rem .5rem;border-radius:999px;background:{{ $m->group==='mahasiswa'?'#8499B6':'#8E77AB' }}18;color:{{ $m->group==='mahasiswa'?'#8499B6':'#8E77AB' }};border:1px solid {{ $m->group==='mahasiswa'?'#8499B6':'#8E77AB' }}33;">
                        {{ $m->group === 'mahasiswa' ? 'Mahasiswa' : 'Tim Eksternal' }}
                    </span>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</section>
@endif

{{-- ── INFO HKI PATEN ─────────────────────────────────────────────────── --}}
@if(isset($hkis) && $hkis->isNotEmpty())
<section style="background:linear-gradient(135deg,#F5F5F6 0%,#EDE8F5 100%);padding:5rem 0;">
    <div style="max-width:1280px;margin:0 auto;padding:0 1.5rem;">
        <div style="text-align:center;margin-bottom:3rem;">
            <p style="font-size:.7rem;letter-spacing:.15em;text-transform:uppercase;color:#8E77AB;font-family:monospace;margin-bottom:.75rem;">Legalitas & Inovasi</p>
            <h2 style="font-size:clamp(1.6rem,4vw,2.5rem);font-family:'Playfair Display',serif;font-weight:700;color:#2E2046;">Info HKI / Paten</h2>
        </div>
        @foreach($hkis as $hki)
        <div class="hki-card" style="background:#fff;border-radius:1.25rem;overflow:hidden;border:1px solid rgba(142,119,171,.18);max-width:860px;margin:0 auto 1.5rem;display:flex;flex-direction:column;">
            {{-- Image panel --}}
            <div class="hki-image" style="background:linear-gradient(135deg,#8E77AB12,#C6D9E830);display:flex;align-items:center;justify-content:center;padding:2rem;min-height:160px;flex-shrink:0;">
                @if($hki->image)
                    <img src="{{ asset('storage/'.$hki->image) }}" alt="{{ $hki->title }}" style="max-height:160px;object-fit:contain;border-radius:.75rem;">
                @else
                    <svg width="72" height="72" viewBox="0 0 24 24" fill="none" stroke="#8E77AB" stroke-width="1.2" opacity=".6"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87L18.18 21 12 17.77 5.82 21 7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                @endif
            </div>
            {{-- Text panel --}}
            <div style="padding:1.75rem 2rem 2rem;">
                <div style="display:inline-flex;align-items:center;gap:.5rem;padding:.25rem .75rem;border-radius:999px;background:#8E77AB12;border:1px solid #8E77AB2A;margin-bottom:1rem;">
                    <span style="width:.5rem;height:.5rem;border-radius:50%;background:#8E77AB;display:block;"></span>
                    <span style="font-size:.65rem;font-family:monospace;text-transform:uppercase;letter-spacing:.12em;color:#8E77AB;">Hak Kekayaan Intelektual</span>
                </div>
                <h3 style="font-family:'Playfair Display',serif;font-weight:700;font-size:1.15rem;color:#2E2046;margin-bottom:.75rem;line-height:1.4;">{{ $hki->title }}</h3>
                @if($hki->description)
                    <p style="font-size:.84rem;line-height:1.7;color:rgba(38,34,58,.65);margin-bottom:1rem;">{{ $hki->description }}</p>
                @endif
                <div style="display:flex;flex-wrap:wrap;gap:1rem;font-size:.75rem;color:rgba(38,34,58,.5);">
                    @if($hki->certificate_number)<span><strong style="color:#2E2046;">No. Sertifikat:</strong> {{ $hki->certificate_number }}</span>@endif
                    @if($hki->issued_date)<span><strong style="color:#2E2046;">Diterbitkan:</strong> {{ $hki->issued_date->format('d F Y') }}</span>@endif
                </div>
            </div>
        </div>
        @endforeach
    </div>
</section>
@endif

{{-- ── PARTNER ─────────────────────────────────────────────────────────── --}}
@if(isset($partners) && $partners->isNotEmpty())
<section style="background:#fff;padding:5rem 0;">
    <div style="max-width:1280px;margin:0 auto;padding:0 1.5rem;">
        <div style="text-align:center;margin-bottom:3rem;">
            <p style="font-size:.7rem;letter-spacing:.15em;text-transform:uppercase;color:#8E77AB;font-family:monospace;margin-bottom:.75rem;">Kolaborasi</p>
            <h2 style="font-size:clamp(1.6rem,4vw,2.5rem);font-family:'Playfair Display',serif;font-weight:700;color:#2E2046;margin-bottom:.75rem;">Partner Pusat Tumbuh Kembang</h2>
            <p style="color:rgba(38,34,58,.6);font-size:.9rem;max-width:36rem;margin:0 auto;">Bermitra dengan lembaga terpercaya untuk memberikan layanan tumbuh kembang anak yang komprehensif.</p>
        </div>
        <div style="display:flex;flex-wrap:wrap;justify-content:center;gap:1.5rem;">
            @foreach($partners as $partner)
            <div style="background:#F5F5F6;border-radius:1.25rem;padding:2rem 1.5rem;border:1px solid rgba(142,119,171,.18);text-align:center;display:flex;flex-direction:column;align-items:center;width:clamp(200px,30vw,280px);transition:box-shadow .2s;" onmouseover="this.style.boxShadow='0 8px 30px rgba(142,119,171,.15)'" onmouseout="this.style.boxShadow='none'">
                <div style="width:6rem;height:6rem;border-radius:1rem;display:flex;align-items:center;justify-content:center;margin-bottom:1rem;overflow:hidden;background:#fff;border:2px solid rgba(142,119,171,.15);">
                    @if($partner->logo)
                        <img src="{{ asset('storage/'.$partner->logo) }}" alt="{{ $partner->name }}" style="max-width:100%;max-height:100%;object-fit:contain;padding:.4rem;">
                    @else
                        <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="#8E77AB" stroke-width="1.5"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 7V5a2 2 0 00-2-2h-4a2 2 0 00-2 2v2"/></svg>
                    @endif
                </div>
                <h3 style="font-family:'Playfair Display',serif;font-weight:700;font-size:.9rem;color:#2E2046;margin-bottom:.5rem;line-height:1.4;">{{ $partner->name }}</h3>
                @if($partner->description)<p style="font-size:.78rem;line-height:1.6;color:rgba(38,34,58,.6);margin-bottom:.75rem;flex:1;">{{ $partner->description }}</p>@endif
                @if($partner->website_url)
                    <a href="{{ $partner->website_url }}" target="_blank" rel="noopener noreferrer"
                       style="font-size:.75rem;font-weight:600;padding:.4rem 1rem;border-radius:.75rem;background:#8E77AB18;color:#8E77AB;border:1px solid #8E77AB33;text-decoration:none;margin-top:auto;">
                        Kunjungi Website →
                    </a>
                @endif
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

@push('styles')
<style>
/* HKI card: side-by-side on md+ */
@media(min-width:768px){
  .hki-card { flex-direction: row !important; }
  .hki-card .hki-image { width: 14rem; min-height: auto; }
}
</style>
@endpush

{{-- CTA --}}
<section style="background:linear-gradient(145deg,#4A3769 0%,#6B5A8E 55%,#8E77AB 100%);padding:5rem 0;position:relative;overflow:hidden;">
    <div style="position:absolute;inset:0;background-image:radial-gradient(circle,rgba(198,217,232,.05) 1px,transparent 1px);background-size:24px 24px;"></div>
    <div style="max-width:40rem;margin:0 auto;text-align:center;padding:0 1.5rem;position:relative;z-index:1;">
        <h2 class="font-display font-bold" style="font-size:clamp(1.5rem,4vw,2.5rem);color:#FFFFFF;margin-bottom:1rem;line-height:1.25;">
            Mulai Identifikasi Sekarang,<br><em class="not-italic" style="color:#C6D9E8;">Gratis</em>
        </h2>
        <p style="color:rgba(255,255,255,.65);font-size:.92rem;margin-bottom:2.5rem;max-width:34rem;margin-left:auto;margin-right:auto;line-height:1.65;">
            49+ indikator observasi terstandar membantu Anda mengenali kebutuhan khusus anak lebih awal dan mengambil langkah yang tepat.
        </p>
        @auth
            <a href="{{ route('member.assessment.start') }}" style="display:inline-flex;align-items:center;gap:.5rem;padding:.85rem 2rem;border-radius:.85rem;font-weight:600;font-size:1rem;background:#ffffff;color:#4A3769;text-decoration:none;">Mulai Asesmen &rarr;</a>
        @else
            <a href="{{ route('register') }}" style="display:inline-flex;align-items:center;gap:.5rem;padding:.85rem 2rem;border-radius:.85rem;font-weight:600;font-size:1rem;background:#ffffff;color:#4A3769;text-decoration:none;">Daftar Gratis Sekarang &rarr;</a>
        @endauth
    </div>
</section>

@endsection
