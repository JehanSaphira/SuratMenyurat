@extends('layouts.app')

@section('content')
<div class="card">
    <div style="display:flex;justify-content:space-between;align-items:center;">
        <h2>Akun Divisi</h2>
        <a class="btn" href="{{ route('admin.accounts.create') }}">Tambah Akun</a>
    </div>
    <table>
        <thead>
            <tr>
                <th>Nama</th>
                <th>Email</th>
                <th>Divisi</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($accounts as $account)
                <tr>
                    <td>{{ $account->name }}</td>
                    <td>{{ $account->email }}</td>
                    <td>{{ $account->division?->name }}</td>
                    <td>
                        <a class="btn secondary" href="{{ route('admin.accounts.edit', $account) }}">Edit</a>
                        <form method="post" action="{{ route('admin.accounts.destroy', $account) }}" style="display:inline;">
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
