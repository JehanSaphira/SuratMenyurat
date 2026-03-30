@extends('layouts.app')

@section('content')
<div class="card">
    <h2>Edit Jenis Surat</h2>
    <form method="post" action="{{ route('admin.letter-types.update', $type) }}">
        @csrf
        @method('put')

        <label>Nama</label>
        <input name="name" value="{{ old('name', $type->name) }}" required>

        <label>Kode</label>
        <input name="code" value="{{ old('code', $type->code) }}" required>

        <label>Template View</label>
        <input name="template_view" value="{{ old('template_view', $type->template_view) }}" required>

        <label>Field Tambahan (JSON)</label>
        <textarea name="extra_fields" rows="6">{{ old('extra_fields', $extraFieldsJson) }}</textarea>

        <button class="btn" type="submit">Simpan</button>
    </form>
</div>
@endsection
