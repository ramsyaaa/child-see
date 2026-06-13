<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<style>
* { margin:0; padding:0; box-sizing:border-box; }
body { font-family: 'DejaVu Sans', sans-serif; font-size:10pt; color:#2E2046; background:#fff; }
.page { padding:20mm 18mm; }

/* Header */
.header { display:table; width:100%; margin-bottom:16pt; border-bottom:2pt solid #4A3769; padding-bottom:10pt; }
.header-logo { display:table-cell; vertical-align:middle; width:120pt; }
.header-logo .brand { font-size:16pt; font-weight:bold; color:#4A3769; }
.header-logo .brand-sub { font-size:8pt; color:#8E77AB; }
.header-info { display:table-cell; vertical-align:middle; text-align:right; }
.header-info .doc-title { font-size:11pt; font-weight:bold; color:#4A3769; }
.header-info .doc-sub { font-size:8pt; color:#666; }

/* Result banner */
.result-banner { border-radius:6pt; padding:12pt 16pt; margin-bottom:14pt; color:#fff; }
.result-banner .label-small { font-size:8pt; opacity:.8; margin-bottom:3pt; }
.result-banner .result-main { font-size:18pt; font-weight:bold; margin-bottom:4pt; }
.result-banner .result-meta { font-size:9pt; opacity:.85; }
.result-banner .code-badge { display:inline-block; background:rgba(255,255,255,.2); border-radius:4pt; padding:2pt 8pt; font-size:8pt; font-family:monospace; margin-right:8pt; }

/* Illustration */
.illustration { width:100%; max-height:120pt; object-fit:cover; border-radius:6pt; margin-bottom:14pt; }

/* Two column layout */
.two-col { display:table; width:100%; margin-bottom:14pt; }
.col-left { display:table-cell; width:62%; padding-right:8pt; vertical-align:top; }
.col-right { display:table-cell; width:38%; vertical-align:top; }

/* Section card */
.card { border:1pt solid #E8E0F0; border-radius:5pt; padding:10pt 12pt; margin-bottom:10pt; }
.card-title { font-size:9pt; font-weight:bold; color:#4A3769; border-bottom:1pt solid #EEE8F8; padding-bottom:5pt; margin-bottom:8pt; text-transform:uppercase; letter-spacing:.5pt; }

/* Info table */
.info-table { width:100%; border-collapse:collapse; }
.info-table td { padding:3pt 4pt; font-size:9pt; vertical-align:top; }
.info-table .lbl { color:#666; width:45%; }
.info-table .val { font-weight:bold; color:#2E2046; }

/* Child photo */
.child-photo { width:65pt; height:65pt; border-radius:5pt; object-fit:cover; display:block; border:1pt solid #D8D0E8; }
.child-avatar { width:65pt; height:65pt; border-radius:5pt; background:#8E77AB; color:#fff; font-size:22pt; font-weight:bold; text-align:center; line-height:65pt; display:block; }

/* Domain scores table */
.scores-table { width:100%; border-collapse:collapse; font-size:9pt; }
.scores-table th { background:#F0EEF5; color:#4A3769; padding:4pt 6pt; text-align:left; font-size:8pt; text-transform:uppercase; letter-spacing:.4pt; }
.scores-table td { padding:4pt 6pt; border-bottom:1pt solid #F0EEF5; }
.badge { display:inline-block; border-radius:3pt; padding:1pt 6pt; font-size:8pt; font-weight:bold; color:#fff; }

/* Answers section */
.answers-section { margin-bottom:14pt; }
.domain-header { background:#4A3769; color:#fff; padding:5pt 10pt; border-radius:4pt 4pt 0 0; font-size:9pt; font-weight:bold; margin-bottom:0; }
.answer-table { width:100%; border-collapse:collapse; font-size:8.5pt; }
.answer-table th { background:#F7F4FC; color:#666; padding:4pt 6pt; border-bottom:1pt solid #E8E0F0; font-size:8pt; text-align:left; }
.answer-table td { padding:4pt 6pt; border-bottom:1pt solid #F5F5F6; vertical-align:top; }
.answer-table tr:last-child td { border-bottom:none; }
.q-no { color:#BAA6D6; font-weight:bold; width:18pt; }

/* Recommendation box */
.rec-box { border-left:3pt solid #4A3769; padding:8pt 10pt; background:#FAFAFA; border-radius:0 4pt 4pt 0; font-size:9pt; line-height:1.5; }

/* Footer */
.footer { position:fixed; bottom:10mm; left:18mm; right:18mm; border-top:1pt solid #E8E0F0; padding-top:5pt; display:table; width:100%; }
.footer-left { display:table-cell; font-size:7.5pt; color:#999; }
.footer-right { display:table-cell; text-align:right; font-size:7.5pt; color:#999; }

.severity-normal { background:#839986; }
.severity-light   { background:#8D77AB; }
.severity-medium  { background:#A86916; }
.severity-heavy   { background:#dc3545; }
.sev-bg-normal { background:#4A3769; }
.sev-bg-light  { background:#6B5B9E; }
.sev-bg-medium { background:#A86916; }
.sev-bg-heavy  { background:#c0392b; }
</style>
</head>
<body>
@php
    $child = $assessment->child;
    $category = $assessment->category;
    $severityColors = ['normal'=>'#839986','light'=>'#8D77AB','medium'=>'#A86916','heavy'=>'#dc3545'];
    $bannerColors   = ['normal'=>'#4A6B5E','light'=>'#4A3769','medium'=>'#7A4A10','heavy'=>'#8B1A1A'];
    $color = $bannerColors[$assessment->severity_level] ?? '#4A3769';
    $grouped = $assessment->answers->groupBy(fn($a) => $a->questionnaire?->domain?->name ?? 'Umum');
@endphp

<div class="footer">
    <span class="footer-left">Child See — Sistem Identifikasi ABK | {{ now()->format('d F Y') }}</span>
    <span class="footer-right">{{ $assessment->assessment_code }}</span>
</div>

<div class="page">

    {{-- Header --}}
    <div class="header">
        <div class="header-logo">
            <div class="brand">Child See</div>
            <div class="brand-sub">Identifikasi Anak Berkebutuhan Khusus</div>
        </div>
        <div class="header-info">
            <div class="doc-title">Laporan Hasil Asesmen</div>
            <div class="doc-sub">Diterbitkan {{ now()->format('d F Y, H:i') }} WIB</div>
        </div>
    </div>

    {{-- Result banner --}}
    <div class="result-banner" style="background:{{ $color }}">
        <div class="label-small">
            <span class="code-badge">{{ $assessment->assessment_code }}</span>
            {{ $assessment->assessment_date?->format('d F Y') ?? $assessment->created_at->format('d F Y') }}
        </div>
        <div class="result-main">{{ $assessment->result_label ?? 'Dalam Proses' }}</div>
        <div class="result-meta">
            {{ $category->name ?? '-' }} &nbsp;·&nbsp;
            Skor Total: {{ $assessment->total_score ?? 0 }} &nbsp;·&nbsp;
            Anak: {{ $child->full_name }}
        </div>
    </div>

    {{-- Illustration --}}
    @if(!empty($category->result_illustration))
    <img src="{{ public_path($category->result_illustration) }}" class="illustration" alt="{{ $category->name }}">
    @endif

    <div class="two-col">
        {{-- Left: Domain scores --}}
        <div class="col-left">
            <div class="card">
                <div class="card-title">Skor per Domain</div>
                @if($assessment->domainScores->isNotEmpty())
                <table class="scores-table">
                    <thead>
                        <tr>
                            <th>Domain</th>
                            <th style="text-align:center">Skor</th>
                            <th style="text-align:center">Hasil</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($assessment->domainScores as $ds)
                        <tr>
                            <td>{{ $ds->domain->name ?? '-' }}</td>
                            <td style="text-align:center;font-weight:bold;color:#4A3769">{{ $ds->total_score ?? 0 }}</td>
                            <td style="text-align:center">
                                <span class="badge severity-{{ $ds->severity_level ?? 'normal' }}">{{ $ds->result_label ?? '-' }}</span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @else
                <p style="font-size:8.5pt;color:#999">Data skor domain tidak tersedia.</p>
                @endif
            </div>

            @if($assessment->result_description)
            <div class="card">
                <div class="card-title">Keterangan</div>
                <p style="font-size:9pt;line-height:1.5;color:#444">{{ $assessment->result_description }}</p>
            </div>
            @endif
        </div>

        {{-- Right: Child info --}}
        <div class="col-right">
            <div class="card">
                <div class="card-title">Profil Anak</div>
                <div style="text-align:center;margin-bottom:8pt">
                    @if($child->photo && file_exists(storage_path('app/public/' . $child->photo)))
                        <img src="{{ storage_path('app/public/' . $child->photo) }}" class="child-photo" alt="{{ $child->full_name }}">
                    @else
                        <span class="child-avatar">{{ strtoupper(substr($child->full_name,0,1)) }}</span>
                    @endif
                </div>
                <table class="info-table">
                    <tr><td class="lbl">Nama</td><td class="val">{{ $child->full_name }}</td></tr>
                    @if($child->birth_date)
                    <tr><td class="lbl">Usia</td><td class="val">{{ \Carbon\Carbon::parse($child->birth_date)->age }} tahun</td></tr>
                    @endif
                    <tr><td class="lbl">Kelamin</td><td class="val">{{ $child->gender === 'male' ? 'Laki-laki' : 'Perempuan' }}</td></tr>
                    @if($child->school_name)
                    <tr><td class="lbl">Sekolah</td><td class="val">{{ $child->school_name }}</td></tr>
                    @endif
                    @if($child->class_name)
                    <tr><td class="lbl">Kelas</td><td class="val">{{ $child->class_name }}</td></tr>
                    @endif
                    @if($child->parent_name)
                    <tr><td class="lbl">Orang Tua</td><td class="val">{{ $child->parent_name }}</td></tr>
                    @endif
                </table>
            </div>

            <div class="card">
                <div class="card-title">Info Asesmen</div>
                <table class="info-table">
                    <tr><td class="lbl">Kategori</td><td class="val">{{ $category->name ?? '-' }}</td></tr>
                    <tr><td class="lbl">Kode</td><td class="val" style="font-family:monospace;font-size:8pt">{{ $assessment->assessment_code }}</td></tr>
                    <tr><td class="lbl">Tanggal</td><td class="val">{{ $assessment->assessment_date?->format('d/m/Y') ?? $assessment->created_at->format('d/m/Y') }}</td></tr>
                    <tr><td class="lbl">Skor</td><td class="val">{{ $assessment->total_score ?? 0 }}</td></tr>
                </table>
            </div>
        </div>
    </div>

    {{-- Recommendation --}}
    @if($assessment->recommendation)
    <div class="card" style="margin-bottom:14pt">
        <div class="card-title">Rekomendasi Tindak Lanjut</div>
        <div class="rec-box">{{ $assessment->recommendation }}</div>
    </div>
    @endif

    {{-- Answers by domain --}}
    @if($grouped->isNotEmpty())
    <div class="answers-section">
        <div class="card-title" style="font-size:9pt;font-weight:bold;color:#4A3769;text-transform:uppercase;letter-spacing:.5pt;margin-bottom:8pt">
            Rincian Jawaban
        </div>
        @foreach($grouped as $domainName => $answers)
        <div style="margin-bottom:10pt">
            <div class="domain-header">{{ $domainName }}</div>
            <table class="answer-table">
                <thead>
                    <tr>
                        <th style="width:18pt">#</th>
                        <th>Pertanyaan</th>
                        <th style="width:80pt;text-align:center">Jawaban</th>
                        <th style="width:30pt;text-align:center">Skor</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($answers as $i => $answer)
                    <tr>
                        <td class="q-no">{{ $i+1 }}</td>
                        <td>{{ $answer->questionnaire?->question ?? '-' }}</td>
                        <td style="text-align:center;color:#4A3769;font-weight:bold">{{ $answer->answerOption?->label ?? '-' }}</td>
                        <td style="text-align:center;font-weight:bold;color:#4A3769">{{ $answer->score ?? 0 }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endforeach
    </div>
    @endif

    {{-- Disclaimer --}}
    <div style="background:#F7F4FC;border-radius:4pt;padding:8pt 10pt;font-size:7.5pt;color:#666;line-height:1.5;margin-top:10pt">
        <strong>Catatan Penting:</strong> Laporan ini merupakan hasil skrining awal (early identification) dan <strong>bukan merupakan diagnosis medis atau psikologis formal</strong>.
        Diagnosis komprehensif hanya dapat ditegakkan oleh tenaga profesional terkait (psikolog, dokter spesialis, atau tim multi-disiplin).
        Harap konsultasikan hasil ini dengan profesional yang berwenang.
    </div>

</div>
</body>
</html>
