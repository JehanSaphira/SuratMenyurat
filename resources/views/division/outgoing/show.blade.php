@extends('layouts.app')

@section('content')
<div class="card">
    <h2>Detail Surat Keluar</h2>
    <p><strong>Nomor:</strong> {{ $letter->number }}</p>
    <p><strong>Jenis:</strong> {{ $letter->type?->name }}</p>
    <p><strong>Perihal:</strong> {{ $letter->subject }}</p>
    <p><strong>Status:</strong> {{ $letter->status }}</p>
    <p><strong>Tanggal:</strong> {{ $letter->created_at->format('d/m/Y') }}</p>

    <div style="margin-top:10px;">
        <strong>Tujuan:</strong>
        <ul style="margin:6px 0 0 18px;">
            @foreach($letter->targets as $target)
                <li>{{ $target->division?->name }} ({{ $target->status }})</li>
            @endforeach
        </ul>
    </div>

    <div class="card" style="margin-top:12px;background:#f8fafc;">
        <strong>Isi Surat:</strong>
        <div style="margin-top:6px;white-space:pre-line;">{{ $letter->body }}</div>
    </div>

    @if(!empty($letter->cc))
        <p style="margin-top:10px;"><strong>Tembusan:</strong> {{ $letter->cc }}</p>
    @endif

    <div style="margin-top:12px;">
        <a class="btn" href="{{ route('division.outgoing.download', $letter) }}">Unduh PDF</a>
        <a class="btn secondary" href="{{ route('division.outgoing.index') }}">Kembali</a>
    </div>
</div>
@endsection
