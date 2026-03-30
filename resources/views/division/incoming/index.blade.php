@extends('layouts.app')

@section('content')
<div class="card">
    <h2>Surat Masuk</h2>
    <table>
        <thead>
            <tr>
                <th>Nomor</th>
                <th>Jenis</th>
                <th>Pengirim</th>
                <th>Perihal</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($targets as $target)
                <tr>
                    <td>{{ $target->letter->number }}</td>
                    <td>{{ $target->letter->type?->name }}</td>
                    <td>{{ $target->letter->division?->name }}</td>
                    <td>{{ $target->letter->subject }}</td>
                    <td>{{ $target->status }}</td>
                    <td>
                        <a class="btn secondary" href="{{ route('division.incoming.show', $target) }}">Detail</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div style="margin-top:10px;">{{ $targets->links() }}</div>
</div>
@endsection
