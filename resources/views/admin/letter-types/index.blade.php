@extends('layouts.app')

@section('content')
<div class="card">
    <div style="display:flex;justify-content:space-between;align-items:center;">
        <h2>Jenis Surat</h2>
        <a class="btn" href="{{ route('admin.letter-types.create') }}">Tambah Jenis</a>
    </div>
    <table>
        <thead>
            <tr>
                <th>Nama</th>
                <th>Kode</th>
                <th>Template</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($types as $type)
                <tr>
                    <td>{{ $type->name }}</td>
                    <td>{{ $type->code }}</td>
                    <td>{{ $type->template_view }}</td>
                    <td>
                        <a class="btn secondary" href="{{ route('admin.letter-types.edit', $type) }}">Edit</a>
                        <form method="post" action="{{ route('admin.letter-types.destroy', $type) }}" style="display:inline;">
                            @csrf
                            @method('delete')
                            <button class="btn danger" type="submit">Hapus</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
