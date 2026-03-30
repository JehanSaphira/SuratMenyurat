<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <style>
        @page { margin: 38px 60px; }
        body { font-family: "Times New Roman", Times, serif; font-size: 11.5px; color: #111; }
        .header { width: 100%; margin-bottom: 12px; }
        .header-table { width: 100%; border-collapse: collapse; }
        .memo-title {
            font-size: 14px;
            font-weight: 700;
            letter-spacing: 0.6px;
            text-align: left;
        }
        .header-right { text-align: right; font-size: 10px; font-weight: 700; }
        .logo {
            height: 28px;
            width: auto;
            display: block;
            margin-left: auto;
            margin-bottom: 2px;
        }
        .header-right .logo-text { font-size: 11px; letter-spacing: 0.3px; }
        .meta-table { width: 100%; border-collapse: collapse; }
        .meta-table td { padding: 1.5px 0; vertical-align: top; }
        .meta-key { width: 96px; }
        .meta-colon { width: 10px; }
        .subject { font-weight: 700; }
        .content { margin-top: 10px; text-align: justify; line-height: 1.5; }
        .section { margin-top: 8px; }
        .agenda-list { margin: 2px 0 0 16px; padding: 0; }
        .signature { margin-top: 18px; }
        .signature-title { margin-bottom: 4px; }
        .signature-name { font-weight: 700; margin-top: 34px; }
        .cc { margin-top: 8px; }
        .cc-list { margin: 4px 0 0 16px; padding: 0; }
        .muted { color: #333; }
        .indent { text-indent: 28px; }
        .label { font-weight: 700; }
        .emph { font-weight: 700; }
        .block-gap { margin-top: 6px; }
    </style>
</head>
<body>
    <div class="header">
        <table class="header-table">
            <tr>
                <td style="width:60%;" class="memo-title">MEMORANDUM</td>
                <td class="header-right" style="width:40%;">
                    @php $logoPath = public_path('images/pertamina.jpeg'); @endphp
                    @if(file_exists($logoPath))
                        <img class="logo" src="{{ $logoPath }}" alt="Pertamina">
                    @else
                        <div class="logo-text">PERTAMINA</div>
                        <div class="muted">PERTA ARUN GAS</div>
                    @endif
                </td>
            </tr>
        </table>
    </div>

    @php
        \Carbon\Carbon::setLocale('id');

        $formatDate = function ($value, bool $withDay = false) {
            if (!$value) {
                return null;
            }
            try {
                $date = \Carbon\Carbon::parse($value);
                return $withDay ? $date->translatedFormat('l, d F Y') : $date->translatedFormat('d F Y');
            } catch (\Exception $e) {
                return $value;
            }
        };
    @endphp

    @php
        $data = $letter->data ?? [];
        $city = $data['kota'] ?? 'Lhokseumawe';
        $tanggalRaw = $data['tanggal'] ?? $data['hari_tanggal'] ?? $data['hari_tgl'] ?? $data['hariTanggal'] ?? null;
        $tanggal = $formatDate($tanggalRaw, true);
        $waktu = $data['waktu'] ?? $data['jam'] ?? $data['pukul'] ?? null;
        $tempat = $data['tempat'] ?? $data['lokasi'] ?? null;
        $agenda = $data['agenda'] ?? $data['acara'] ?? null;
        $lampiran = $data['lampiran'] ?? (!empty($letter->attachments) ? count($letter->attachments) . ' lampiran' : '-');
        $dari = $data['dari'] ?? $letter->division?->name;
        $kepada = $data['kepada'] ?? null;
        $penandatanganJabatan = $data['penandatangan_jabatan'] ?? $dari;
        $penandatanganNama = $data['penandatangan_nama'] ?? null;
        $tembusan = $data['tembusan'] ?? null;
    @endphp

    <table class="meta-table">
        <tr>
            <td class="meta-key">Tempat/Tgl</td>
            <td class="meta-colon">:</td>
            <td>{{ $city }}, {{ $formatDate($letter->created_at) }}</td>
        </tr>
        <tr>
            <td class="meta-key">Nomor</td>
            <td class="meta-colon">:</td>
            <td>{{ $letter->number }}</td>
        </tr>
    </table>

    <table class="meta-table" style="margin-top:6px;">
        <tr>
            <td class="meta-key">Kepada</td>
            <td class="meta-colon">:</td>
            <td>
                @if(!empty($kepada ?? null))
                    {{ $kepada ?? null }}
                @elseif($letter->relationLoaded('targets'))
                    {{ $letter->targets->map(fn($t) => $t->division?->name)->filter()->implode(', ') }}
                @else
                    Daftar Distribusi Terlampir
                @endif
            </td>
        </tr>
        <tr>
            <td class="meta-key">Dari</td>
            <td class="meta-colon">:</td>
            <td>{{ $dari ?? ($letter->division?->name ?? '-') }}</td>
        </tr>
        <tr>
            <td class="meta-key">Lampiran</td>
            <td class="meta-colon">:</td>
            <td>{{ $lampiran ?? (!empty($letter->attachments) ? count($letter->attachments) . ' lampiran' : '-') }}</td>
        </tr>
        <tr>
            <td class="meta-key">Perihal</td>
            <td class="meta-colon">:</td>
            <td class="subject">{{ $letter->subject }}</td>
        </tr>
    </table>

    @php
        $bodyText = trim((string) ($letter->body ?? ''));
        $bodyLower = strtolower($bodyText);
        $expandedImlek = "Sehubungan dengan hari besar Imlek, kami informasikan bahwa terdapat libur dan cuti bersama pada hari ini. Mohon seluruh unit menyesuaikan jadwal kerja, layanan operasional, serta koordinasi internal agar aktivitas tetap berjalan tertib. Bagi unit yang memiliki tugas layanan yang tidak dapat ditunda, silakan mengatur piket dan melakukan pelaporan sesuai prosedur yang berlaku. Atas perhatian dan kerjasamanya, kami ucapkan terima kasih.";
        $finalBody = $bodyLower === 'libur cuti imlek pada hari ini' ? $expandedImlek : $bodyText;
    @endphp
    <div class="content block-gap">
        @if(!empty($finalBody))
            {!! nl2br(e($finalBody)) !!}
        @else
            <div class="indent">Sebagai wujud implementasi program kerja dan komunikasi internal, memo ini disampaikan untuk menjadi perhatian dan tindak lanjut sesuai fungsi masing-masing.</div>
        @endif
    </div>

    <div class="section">
        <table class="meta-table">
            <tr>
                <td class="meta-key">Hari/Tanggal</td>
                <td class="meta-colon">:</td>
                <td class="emph">{{ $tanggal ?: '-' }}</td>
            </tr>
            <tr>
                <td class="meta-key">Waktu</td>
                <td class="meta-colon">:</td>
                <td class="emph">{{ $waktu ?: '-' }}</td>
            </tr>
            <tr>
                <td class="meta-key">Tempat</td>
                <td class="meta-colon">:</td>
                <td class="emph">{{ $tempat ?: '-' }}</td>
            </tr>
            <tr>
                <td class="meta-key">Agenda</td>
                <td class="meta-colon">:</td>
                <td>
                    @if(is_array($agenda))
                        <ol class="agenda-list">
                            @foreach($agenda as $item)
                                <li class="emph">{{ $item }}</li>
                            @endforeach
                        </ol>
                    @elseif(is_string($agenda) && str_contains($agenda, "\n"))
                        <ol class="agenda-list">
                            @foreach(preg_split('/\r\n|\r|\n/', $agenda) as $item)
                                @if(trim($item) !== '')
                                    <li class="emph">{{ $item }}</li>
                                @endif
                            @endforeach
                        </ol>
                    @elseif($agenda)
                        <span class="emph">{{ $agenda }}</span>
                    @else
                        <span class="emph">-</span>
                    @endif
                </td>
            </tr>
        </table>
    </div>

    <div class="signature">
        <div>Demikian disampaikan, atas perhatian dan kerjasamanya kami ucapkan terima kasih.</div>
        <div class="signature-title" style="margin-top:16px;">{{ $penandatanganJabatan }}</div>
        @if($penandatanganNama)
            <div class="signature-name">{{ $penandatanganNama }}</div>
        @endif
    </div>

    @if(!empty($tembusan))
        <div class="cc">
            <div><strong>Tembusan :</strong></div>
            @if(is_array($tembusan))
                <ol class="cc-list">
                    @foreach($tembusan as $item)
                        <li>{{ $item }}</li>
                    @endforeach
                </ol>
            @else
                <div>{!! nl2br(e($tembusan)) !!}</div>
            @endif
        </div>
    @endif
</body>
</html>
