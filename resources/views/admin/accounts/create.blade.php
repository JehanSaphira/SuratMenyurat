@extends('layouts.app')

@section('content')
<div class="card">
    <h2>Tambah Akun Divisi</h2>
    <form method="post" action="{{ route('admin.accounts.store') }}">
        @csrf

        <label>Nama</label>
        <input name="name" value="{{ old('name') }}" required>
        @error('name')
            <div style="color:#b91c1c; margin-top:6px;">{{ $message }}</div>
        @enderror

        <label>Email</label>
        <input type="email" name="email" value="{{ old('email') }}" required>
        @error('email')
            <div style="color:#b91c1c; margin-top:6px;">{{ $message }}</div>
        @enderror

        <label>Divisi</label>
        <select name="division_id" required>
            @foreach($divisions as $division)
                <option value="{{ $division->id }}" @selected((string) $division->id === (string) old('division_id'))>
                    {{ $division->name }}
                </option>
            @endforeach
        </select>
        @error('division_id')
            <div style="color:#b91c1c; margin-top:6px;">{{ $message }}</div>
        @enderror

        <label>Password</label>
        <input type="password" name="password" required>
        @error('password')
            <div style="color:#b91c1c; margin-top:6px;">{{ $message }}</div>
        @enderror

        <button class="btn" type="submit">Simpan</button>
    </form>
</div>
@endsection
