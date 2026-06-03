{{-- Child See Footer --}}
<footer class="mt-20">
    <div class="footer-border"></div>
    <div style="background:#4A3769;color:#F5F5F6;">
        <div class="max-w-7xl mx-auto px-6 lg:px-10 py-12 grid md:grid-cols-4 gap-10">
            <div class="md:col-span-2">
                <div class="flex items-center gap-3 mb-4">
                    <span class="grid place-items-center w-10 h-10 rounded-full" style="background:rgba(245,245,246,0.10);">
                        <svg viewBox="0 0 40 40" class="w-8 h-8">
                            <circle cx="20" cy="20" r="14" fill="none" stroke="#BAA6D6" stroke-width="1.2"/>
                            <circle cx="20" cy="20" r="8"  fill="none" stroke="#F5F5F6" stroke-width="1.2"/>
                            <circle cx="20" cy="12" r="2" fill="#BAA6D6"/><circle cx="20" cy="28" r="2" fill="#BAA6D6"/>
                            <circle cx="12" cy="20" r="2" fill="#BAA6D6"/><circle cx="28" cy="20" r="2" fill="#BAA6D6"/>
                        </svg>
                    </span>
                    <div class="font-display text-xl">Child <span style="color:#BAA6D6;">See</span></div>
                </div>
                <p class="text-sm leading-relaxed max-w-md" style="color:rgba(245,245,246,0.80);">
                    Platform identifikasi awal Anak Berkebutuhan Khusus (ABK) tingkat Sekolah Dasar,
                    dirancang untuk mendukung guru dan orang tua dalam menemukenali potensi sedari dini.
                </p>
                @if(!empty($site['site_tagline']))
                <p class="mt-2 text-sm" style="color:rgba(245,245,246,0.65);">{{ $site['site_tagline'] }}</p>
                @endif
                @if(!empty($site['site_address']))
                <p class="mt-3 text-xs font-mono" style="color:rgba(245,245,246,0.50);">{{ $site['site_address'] }}</p>
                @endif
            </div>
            <div>
                <div class="text-xs uppercase mb-3" style="letter-spacing:0.18em;color:#C9B4DE;">Navigasi</div>
                <ul class="space-y-2 text-sm" style="color:rgba(245,245,246,0.80);">
                    <li><a href="{{ route('home') }}" class="hover:text-[#C9B4DE] transition-colors">Home</a></li>
                    <li><a href="{{ route('about') }}" class="hover:text-[#C9B4DE] transition-colors">Tentang</a></li>
                    @auth
                    <li><a href="{{ route('member.assessment.start') }}" class="hover:text-[#C9B4DE] transition-colors">Mulai Asesmen</a></li>
                    @endauth
                </ul>
                <div class="flex gap-3 mt-4">
                    @if(!empty($site['site_instagram']))<a href="{{ $site['site_instagram'] }}" target="_blank" style="color:rgba(245,245,246,0.55);" class="hover:text-[#C9B4DE] transition-colors" title="Instagram"><svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg></a>@endif
                    @if(!empty($site['site_whatsapp']))<a href="{{ $site['site_whatsapp'] }}" target="_blank" style="color:rgba(245,245,246,0.55);" class="hover:text-[#C9B4DE] transition-colors" title="WhatsApp"><svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg></a>@endif
                </div>
            </div>
            <div>
                <div class="text-xs uppercase mb-3" style="letter-spacing:0.18em;color:#C9B4DE;">Kontak</div>
                <ul class="space-y-2 text-sm" style="color:rgba(245,245,246,0.80);">
                    @if(!empty($site['site_phone']))<li>{{ $site['site_phone'] }}</li>@endif
                    @if(!empty($site['site_email']))<li>{{ $site['site_email'] }}</li>@endif
                    @if(!empty($site['site_address']))<li style="font-size:0.8rem;color:rgba(245,245,246,0.60);">{{ $site['site_address'] }}</li>@endif
                </ul>
            </div>
        </div>
        <div style="border-top:1px solid rgba(245,245,246,0.10);">
            <div class="max-w-7xl mx-auto px-6 lg:px-10 py-5 flex flex-wrap items-center justify-between gap-3 text-xs" style="color:rgba(245,245,246,0.60);">
                <div>© {{ date('Y') }} Child See. Seluruh hak cipta dilindungi.</div>
                <div class="flex items-center gap-4">
                    <span>HKI Paten Terdaftar</span>
                    <span>•</span>
                    <span>v1.0</span>
                </div>
            </div>
        </div>
    </div>
</footer>

<script>
function showToast(msg) {
    const t = document.getElementById('toast');
    if (!t) return;
    t.textContent = msg;
    t.classList.add('show');
    clearTimeout(window.__toastTimer);
    window.__toastTimer = setTimeout(() => t.classList.remove('show'), 2200);
}
</script>
@stack('scripts')
