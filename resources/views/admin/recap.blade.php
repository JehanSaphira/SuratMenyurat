@extends('layouts.app')

@section('content')
<div class="card">
    <h2>Rekap Surat</h2>
    @php
        $currentYear = now()->year;
        $years = range($currentYear - 5, $currentYear + 1);
    @endphp
    <form method="get" action="{{ route('admin.recap') }}" class="grid grid-2" style="margin-bottom:12px;">
        <div>
            <label>Tanggal</label>
            <select name="day">
                <option value="">Semua</option>
                @for($d = 1; $d <= 31; $d++)
                    <option value="{{ $d }}" @selected((string)$day === (string)$d)>{{ $d }}</option>
                @endfor
            </select>
        </div>
        <div>
            <label>Bulan</label>
            <select name="month">
                <option value="">Semua</option>
                @for($m = 1; $m <= 12; $m++)
                    <option value="{{ $m }}" @selected((string)$month === (string)$m)>
                        {{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}
                    </option>
                @endfor
            </select>
        </div>
        <div>
            <label>Tahun</label>
            <select name="year">
                <option value="">Semua</option>
                @foreach($years as $y)
                    <option value="{{ $y }}" @selected((string)$year === (string)$y)>{{ $y }}</option>
                @endforeach
            </select>
        </div>
        <div style="display:flex;gap:10px;flex-wrap:wrap;align-items:end;">
            <button class="btn" type="submit">Filter</button>
            <a class="btn secondary" href="{{ route('admin.recap') }}">Reset</a>
        </div>
    </form>
</div>

<div class="card">
    <h3>Surat Keluar Terbaru</h3>
    <table>
        <thead>
            <tr>
                <th>Nomor</th>
                <th>Divisi</th>
                <th>Jenis</th>
                <th>Perihal</th>
                <th>Tanggal</th>
            </tr>
        </thead>
        <tbody>
            @forelse($outgoing as $letter)
                <tr>
                    <td>{{ $letter->number }}</td>
                    <td>{{ $letter->division?->name }}</td>
                    <td>{{ $letter->type?->name }}</td>
                    <td>{{ $letter->subject }}</td>
                    <td>{{ $letter->created_at->format('d/m/Y') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" style="text-align:center;color:#64748b;padding:14px;">
                        Tidak ada data surat keluar.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="card">
    <h3>Surat Masuk Terbaru</h3>
    <table>
        <thead>
            <tr>
                <th>Nomor</th>
                <th>Pengirim</th>
                <th>Perihal</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($incoming as $target)
                <tr>
                    <td>{{ $target->letter->number }}</td>
                    <td>{{ $target->letter->division?->name }}</td>
                    <td>{{ $target->letter->subject }}</td>
                    <td>{{ $target->status }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" style="text-align:center;color:#64748b;padding:14px;">
                        Tidak ada data surat masuk.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
