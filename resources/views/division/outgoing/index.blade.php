@extends('layouts.app')

@section('content')
<div class="card">
    <div style="display:flex;justify-content:space-between;align-items:center;">
        <h2>Surat Keluar</h2>
        <a class="btn" href="{{ route('division.outgoing.create') }}">Buat Surat</a>
    </div>
    <table>
        <thead>
            <tr>
                <th>Nomor</th>
                <th>Jenis</th>
                <th>Perihal</th>
                <th>Status</th>
                <th>Tanggal</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($letters as $letter)
                <tr>
                    <td>{{ $letter->number }}</td>
                    <td>{{ $letter->type?->name }}</td>
                    <td>{{ $letter->subject }}</td>
                    <td>{{ $letter->status }}</td>
                    <td>{{ $letter->created_at->format('d/m/Y') }}</td>
                    <td>
                        <a class="btn secondary" href="{{ route('division.outgoing.show', $letter) }}">Detail</a>
                        <a class="btn" href="{{ route('division.outgoing.download', $letter) }}">Unduh PDF</a>
                        <form method="post" action="{{ route('division.outgoing.destroy', $letter) }}" style="display:inline;">
                            @csrf
                            @method('delete')
                            <button class="btn danger" type="submit">Hapus</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div style="margin-top:10px;">{{ $letters->links() }}</div>
</div>
@endsection
