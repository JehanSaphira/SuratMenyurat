@extends('layouts.app')

@section('content')
<div class="card">
    <div style="display:flex;justify-content:space-between;align-items:center;">
        <h2>Divisi</h2>
        <a class="btn" href="{{ route('admin.divisions.create') }}">Tambah Divisi</a>
    </div>
    <table>
        <thead>
            <tr>
                <th>Nama</th>
                <th>Kode</th>
                <th>Format Nomor</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($divisions as $division)
                <tr>
                    <td>{{ $division->name }}</td>
                    <td>{{ $division->code }}</td>
                    <td>{{ $division->number_format }}</td>
                    <td>
                        <a class="btn secondary" href="{{ route('admin.divisions.edit', $division) }}">Edit</a>
                        <form method="post" action="{{ route('admin.divisions.destroy', $division) }}" style="display:inline;">
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
