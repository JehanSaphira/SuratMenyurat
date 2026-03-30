@extends('layouts.app')

@section('content')
<div class="card">
    <h2>Edit Akun Divisi</h2>
    <form method="post" action="{{ route('admin.accounts.update', $account) }}">
        @csrf
        @method('put')

        <label>Nama</label>
        <input name="name" value="{{ old('name', $account->name) }}" required>

        <label>Email</label>
        <input type="email" name="email" value="{{ old('email', $account->email) }}" required>

        <label>Divisi</label>
        <select name="division_id" required>
            @foreach($divisions as $division)
                <option value="{{ $division->id }}" @selected($division->id === $account->division_id)>{{ $division->name }}</option>
            @endforeach
        </select>

        <label>Password (kosongkan jika tidak diubah)</label>
        <input type="password" name="password">

        <button class="btn" type="submit">Simpan</button>
    </form>
</div>
@endsection
