<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<style>
* { margin:0; padding:0; box-sizing:border-box; }
body { font-family: 'DejaVu Sans', sans-serif; font-size:10pt; color:#2E2046; background:#fff; }
.page { padding:18mm 16mm 22mm 16mm; }

.header { display:table; width:100%; margin-bottom:14pt; border-bottom:2pt solid #4A3769; padding-bottom:10pt; }
.header-logo { display:table-cell; vertical-align:middle; width:130pt; }
.header-logo .brand { font-size:16pt; font-weight:bold; color:#4A3769; }
.header-logo .brand-sub { font-size:8pt; color:#8E77AB; }
.header-info { display:table-cell; vertical-align:middle; text-align:right; }
.header-info .doc-title { font-size:11pt; font-weight:bold; color:#4A3769; }
.header-info .doc-sub { font-size:8pt; color:#666; }

/* Child profile banner */
.profile-banner { display:table; width:100%; background:#F0EEF5; border-radius:6pt; padding:12pt; margin-bottom:14pt; }
.profile-photo-cell { display:table-cell; width:72pt; vertical-align:middle; }
.profile-photo { width:60pt; height:60pt; border-radius:5pt; object-fit:cover; border:1pt solid #D8D0E8; }
.profile-avatar { width:60pt; height:60pt; border-radius:5pt; background:#8E77AB; color:#fff; font-size:22pt; font-weight:bold; text-align:center; line-height:60pt; }
.profile-info-cell { display:table-cell; vertical-align:middle; padding-left:10pt; }
.profile-name { font-size:14pt; font-weight:bold; color:#4A3769; margin-bottom:4pt; }
.profile-meta { font-size:9pt; color:#666; line-height:1.6; }

/* Stats row */
.stats-row { display:table; width:100%; margin-bottom:14pt; }
.stat-box { display:table-cell; text-align:center; padding:8pt; background:#fff; border:1pt solid #E8E0F0; border-radius:5pt; }
.stat-box + .stat-box { border-left:none; }
.stat-num { font-size:18pt; font-weight:bold; color:#4A3769; }
.stat-lbl { font-size:8pt; color:#888; text-transform:uppercase; letter-spacing:.3pt; }

/* Cards */
.card { border:1pt solid #E8E0F0; border-radius:5pt; padding:10pt 12pt; margin-bottom:12pt; }
.card-title { font-size:9pt; font-weight:bold; color:#4A3769; text-transform:uppercase; letter-spacing:.5pt; border-bottom:1pt solid #EEE8F8; padding-bottom:5pt; margin-bottom:8pt; }

/* Assessment block */
.assessment-header { background:#4A3769; color:#fff; border-radius:5pt 5pt 0 0; padding:8pt 12pt; margin-bottom:0; }
.assessment-header .a-title { font-size:10pt; font-weight:bold; }
.assessment-header .a-sub { font-size:8pt; opacity:.8; margin-top:2pt; }
.assessment-body { border:1pt solid #E8E0F0; border-top:none; border-radius:0 0 5pt 5pt; padding:10pt 12pt; margin-bottom:12pt; }

.result-chip { display:inline-block; border-radius:3pt; padding:2pt 8pt; font-size:9pt; font-weight:bold; color:#fff; margin-bottom:6pt; }

.two-col { display:table; width:100%; }
.col-l { display:table-cell; width:55%; padding-right:8pt; vertical-align:top; }
.col-r { display:table-cell; width:45%; vertical-align:top; }

.info-table { width:100%; border-collapse:collapse; font-size:9pt; }
.info-table td { padding:3pt 4pt; vertical-align:top; }
.info-table .lbl { color:#666; width:48%; }
.info-table .val { font-weight:bold; }

.scores-table { width:100%; border-collapse:collapse; font-size:9pt; }
.scores-table th { background:#F0EEF5; color:#4A3769; padding:3pt 5pt; font-size:8pt; text-align:left; }
.scores-table td { padding:3pt 5pt; border-bottom:1pt solid #F5F5F6; }
.badge { display:inline-block; border-radius:3pt; padding:1pt 5pt; font-size:8pt; font-weight:bold; color:#fff; }
.severity-normal { background:#839986; }
.severity-light   { background:#8D77AB; }
.severity-medium  { background:#A86916; }
.severity-heavy   { background:#dc3545; }

.rec-box { border-left:3pt solid #4A3769; padding:6pt 8pt; background:#FAFAFA; font-size:8.5pt; line-height:1.5; }

.footer { position:fixed; bottom:8mm; left:16mm; right:16mm; border-top:1pt solid #E8E0F0; padding-top:4pt; display:table; width:100%; }
.footer-left { display:table-cell; font-size:7.5pt; color:#999; }
.footer-right { display:table-cell; text-align:right; font-size:7.5pt; color:#999; }

.page-break { page-break-before:always; }

.disclaimer { background:#F7F4FC; border-radius:4pt; padding:8pt 10pt; font-size:7.5pt; color:#666; line-height:1.5; margin-top:10pt; }
</style>
</head>
<body>
@php
    $severityColors = ['normal'=>'#839986','light'=>'#8D77AB','medium'=>'#A86916','heavy'=>'#dc3545'];
    $bannerColors   = ['normal'=>'#4A6B5E','light'=>'#4A3769','medium'=>'#7A4A10','heavy'=>'#8B1A1A'];
    $totalAssessments = $assessments->count();
    $lastSeverity = $assessments->first()?->severity_level ?? 'normal';
@endphp

<div class="footer">
    <span class="footer-left">Child See — Laporan Asesmen: {{ $child->full_name }} | {{ now()->format('d F Y') }}</span>
    <span class="footer-right">Halaman <span class="pagenum"></span></span>
</div>

<div class="page">

    {{-- Header --}}
    <div class="header">
        <div class="header-logo">
            <div class="brand">Child See</div>
            <div class="brand-sub">Identifikasi Anak Berkebutuhan Khusus</div>
        </div>
        <div class="header-info">
            <div class="doc-title">Laporan Lengkap Asesmen Anak</div>
            <div class="doc-sub">Diterbitkan {{ now()->format('d F Y, H:i') }} WIB</div>
        </div>
    </div>

    {{-- Child profile --}}
    <div class="profile-banner">
        <div class="profile-photo-cell">
            @if($child->photo)
                <img src="{{ storage_path('app/public/' . $child->photo) }}" class="profile-photo" alt="{{ $child->full_name }}">
            @else
                <div class="profile-avatar">{{ strtoupper(substr($child->full_name,0,1)) }}</div>
            @endif
        </div>
        <div class="profile-info-cell">
            <div class="profile-name">{{ $child->full_name }}</div>
            <div class="profile-meta">
                @if($child->nick_name) Panggilan: <strong>{{ $child->nick_name }}</strong> &nbsp;·&nbsp; @endif
                @if($child->birth_date) {{ \Carbon\Carbon::parse($child->birth_date)->age }} tahun &nbsp;·&nbsp; @endif
                {{ $child->gender === 'male' ? 'Laki-laki' : 'Perempuan' }}
                @if($child->school_name)<br>{{ $child->school_name }}@if($child->class_name), Kelas {{ $child->class_name }}@endif @endif
                @if($child->parent_name)<br>Orang Tua: {{ $child->parent_name }} @if($child->parent_phone)({{ $child->parent_phone }})@endif @endif
            </div>
        </div>
    </div>

    {{-- Stats --}}
    <div class="stats-row">
        <div class="stat-box">
            <div class="stat-num">{{ $totalAssessments }}</div>
            <div class="stat-lbl">Total Asesmen</div>
        </div>
        <div class="stat-box">
            <div class="stat-num">{{ $assessments->pluck('category_id')->unique()->count() }}</div>
            <div class="stat-lbl">Kategori Diuji</div>
        </div>
        <div class="stat-box">
            <div class="stat-num" style="color:{{ $severityColors[$lastSeverity] ?? '#4A3769' }}">
                {{ ucfirst($lastSeverity) }}
            </div>
            <div class="stat-lbl">Hasil Terakhir</div>
        </div>
        <div class="stat-box">
            <div class="stat-num">{{ $assessments->first()?->total_score ?? '-' }}</div>
            <div class="stat-lbl">Skor Terakhir</div>
        </div>
    </div>

    @if($assessments->isEmpty())
        <div class="card" style="text-align:center;padding:20pt">
            <p style="color:#999;font-size:9pt">Belum ada asesmen yang diselesaikan untuk anak ini.</p>
        </div>
    @else
        @foreach($assessments as $idx => $assessment)
            @if($idx > 0) <div class="page-break"></div> @endif

            @php $color = $bannerColors[$assessment->severity_level] ?? '#4A3769'; @endphp

            <div class="assessment-header" style="background:{{ $color }}">
                <div class="a-title">Asesmen #{{ $idx+1 }} — {{ $assessment->category->name ?? '-' }}</div>
                <div class="a-sub">
                    {{ $assessment->assessment_code }} &nbsp;·&nbsp;
                    {{ $assessment->assessment_date?->format('d F Y') ?? \Carbon\Carbon::parse($assessment->created_at)->format('d F Y') }}
                </div>
            </div>
            <div class="assessment-body">

                {{-- Illustration --}}
                @if(!empty($assessment->category->result_illustration))
                <img src="{{ public_path($assessment->category->result_illustration) }}"
                     style="width:100%;max-height:100pt;object-fit:cover;border-radius:4pt;margin-bottom:10pt" alt="">
                @endif

                <span class="result-chip severity-{{ $assessment->severity_level ?? 'normal' }}">
                    {{ $assessment->result_label ?? '-' }} &nbsp;·&nbsp; Skor: {{ $assessment->total_score ?? 0 }}
                </span>

                <div class="two-col">
                    <div class="col-l">
                        {{-- Domain scores --}}
                        @if($assessment->domainScores->isNotEmpty())
                        <div style="margin-bottom:10pt">
                            <div style="font-size:8.5pt;font-weight:bold;color:#4A3769;margin-bottom:5pt;text-transform:uppercase;letter-spacing:.4pt">Skor per Domain</div>
                            <table class="scores-table">
                                <thead><tr><th>Domain</th><th style="text-align:center">Skor</th><th>Hasil</th></tr></thead>
                                <tbody>
                                    @foreach($assessment->domainScores as $ds)
                                    <tr>
                                        <td>{{ $ds->domain->name ?? '-' }}</td>
                                        <td style="text-align:center;font-weight:bold;color:#4A3769">{{ $ds->total_score ?? 0 }}</td>
                                        <td><span class="badge severity-{{ $ds->severity_level ?? 'normal' }}">{{ $ds->result_label ?? '-' }}</span></td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @endif

                        @if($assessment->result_description)
                        <div style="margin-bottom:8pt">
                            <div style="font-size:8.5pt;font-weight:bold;color:#4A3769;margin-bottom:4pt;text-transform:uppercase;letter-spacing:.4pt">Keterangan</div>
                            <p style="font-size:8.5pt;color:#444;line-height:1.5">{{ $assessment->result_description }}</p>
                        </div>
                        @endif
                    </div>
                    <div class="col-r">
                        @if($assessment->recommendation)
                        <div style="font-size:8.5pt;font-weight:bold;color:#4A3769;margin-bottom:4pt;text-transform:uppercase;letter-spacing:.4pt">Rekomendasi</div>
                        <div class="rec-box">{{ $assessment->recommendation }}</div>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    @endif

    <div class="disclaimer">
        <strong>Catatan Penting:</strong> Laporan ini merupakan hasil skrining awal (early identification) dan <strong>bukan merupakan diagnosis medis atau psikologis formal</strong>.
        Diagnosis komprehensif hanya dapat ditegakkan oleh tenaga profesional terkait. Harap konsultasikan hasil ini dengan psikolog, dokter spesialis, atau tim multi-disiplin yang berwenang.
    </div>

</div>
</body>
</html>
