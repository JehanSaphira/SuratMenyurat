@extends('layouts.app')

@section('content')
<div class="card">
    <h2>Edit Divisi</h2>
    <form method="post" action="{{ route('admin.divisions.update', $division) }}">
        @csrf
        @method('put')

        <label>Nama</label>
        <input name="name" value="{{ old('name', $division->name) }}" required>
        @error('name')
            <div style="color:#b91c1c; margin-top:6px;">{{ $message }}</div>
        @enderror

        <label>Kode</label>
        <input name="code" value="{{ old('code', $division->code) }}" required>
        @error('code')
            <div style="color:#b91c1c; margin-top:6px;">{{ $message }}</div>
        @enderror

        <label>Format Nomor Surat</label>
        <input name="number_format" value="{{ old('number_format', $division->number_format) }}" required>
        @error('number_format')
            <div style="color:#b91c1c; margin-top:6px;">{{ $message }}</div>
        @enderror
        <div class="card" style="background:#f8fafc;">
            Isi menggunakan placeholder, bukan contoh hasil.
            Gunakan placeholder: {urut} {kodeDivisi} {bulan} {tahun} {kodeJenis}
        </div>

        <button class="btn" type="submit">Simpan</button>
    </form>
</div>
@endsection
