@extends('layouts.app')

@section('content')
<div class="card">
    <h2>Monitoring Surat</h2>
    <form method="get" action="{{ route('admin.monitoring.index') }}" class="grid grid-2" style="margin-bottom:12px;">
        <div>
            <label>Divisi Pengirim</label>
            <select name="division_id">
                <option value="">Semua</option>
                @foreach($divisions as $division)
                    <option value="{{ $division->id }}" @selected(request('division_id') == $division->id)>{{ $division->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label>Divisi Tujuan</label>
            <select name="target_division_id">
                <option value="">Semua</option>
                @foreach($divisions as $division)
                    <option value="{{ $division->id }}" @selected(request('target_division_id') == $division->id)>{{ $division->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label>Jenis</label>
            <select name="letter_type_id">
                <option value="">Semua</option>
                @foreach($types as $type)
                    <option value="{{ $type->id }}" @selected(request('letter_type_id') == $type->id)>{{ $type->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label>Status</label>
            <select name="status">
                <option value="">Semua</option>
                <option value="sent" @selected(request('status') === 'sent')>Terkirim</option>
                <option value="completed" @selected(request('status') === 'completed')>Selesai</option>
            </select>
        </div>
        <div>
            <label>Dari</label>
            <input type="date" name="date_from" value="{{ request('date_from') }}">
        </div>
        <div>
            <label>Sampai</label>
            <input type="date" name="date_to" value="{{ request('date_to') }}">
        </div>
        <div style="display:flex;gap:10px;flex-wrap:wrap;align-items:end;">
            <button class="btn" type="submit">Filter</button>
            <a class="btn secondary" href="{{ route('admin.monitoring.index') }}">Reset</a>
        </div>
    </form>
    <div style="margin-bottom:10px;display:flex;justify-content:space-between;align-items:center;gap:10px;flex-wrap:wrap;">
        <div class="tag">Total hasil: {{ $letters->total() }}</div>
        @if($letters->total() === 0)
            <div style="color:#64748b;">Tidak ada data sesuai filter.</div>
        @endif
    </div>
    <table>
        <thead>
            <tr>
                <th>Nomor</th>
                <th>Jenis</th>
                <th>Pengirim</th>
                <th>Tujuan</th>
                <th>Status</th>
                <th>Tanggal</th>
            </tr>
        </thead>
        <tbody>
            @forelse($letters as $letter)
                <tr>
                    <td>{{ $letter->number }}</td>
                    <td>{{ $letter->type?->name }}</td>
                    <td>{{ $letter->division?->name }}</td>
                    <td>
                        @foreach($letter->targets as $target)
                            <div>{{ $target->division?->name }} ({{ $target->status }})</div>
                        @endforeach
                    </td>
                    <td>{{ $letter->status }}</td>
                    <td>{{ $letter->created_at->format('d/m/Y') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="text-align:center;color:#64748b;padding:16px;">
                        Data tidak ditemukan untuk filter yang dipilih.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
    <div style="margin-top:10px;">{{ $letters->links() }}</div>
</div>
@endsection
