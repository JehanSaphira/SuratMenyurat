@extends('layouts.app')

@section('content')
<div class="card">
    <h2>Tambah Jenis Surat</h2>
    <form method="post" action="{{ route('admin.letter-types.store') }}">
        @csrf

        <label>Nama</label>
        <input name="name" value="{{ old('name') }}" required>

        <label>Kode</label>
        <input name="code" value="{{ old('code') }}" required>

        <label>Template View</label>
        <input name="template_view" value="{{ old('template_view', 'letters.pdf.default') }}" required>

        <label>Field Tambahan (JSON)</label>
        <textarea name="extra_fields" rows="6">{{ old('extra_fields') }}</textarea>
        <small>Contoh: [{"key":"tanggal","label":"Tanggal","type":"date","required":true}]</small>

        <button class="btn" type="submit">Simpan</button>
    </form>
</div>
@endsection
