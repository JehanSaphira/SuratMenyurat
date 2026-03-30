@extends('layouts.app')

@section('content')
<style>
    .login-shell {
        min-height: calc(100vh - 180px);
        display: grid;
        place-items: center;
        padding: 20px 0;
    }
    .login-card {
        width: 100%;
        max-width: 460px;
        border-radius: 18px;
        border: 1px solid var(--border);
        overflow: hidden;
        background: #fff;
        box-shadow: 0 14px 30px rgba(30, 64, 175, 0.12);
    }
    .login-head {
        background: linear-gradient(120deg, #3b82f6 0%, #60a5fa 58%, #93c5fd 100%);
        color: #fff;
        padding: 20px 24px;
        text-align: center;
    }
    .login-kicker {
        display: inline-block;
        border: 1px solid rgba(255,255,255,0.35);
        border-radius: 999px;
        padding: 3px 10px;
        font-size: 12px;
        letter-spacing: 0.3px;
        margin-bottom: 8px;
    }
    .login-title {
        margin: 0;
        font-size: 26px;
        line-height: 1.15;
    }
    .login-body {
        padding: 22px 24px 24px;
    }
    .field-wrap {
        margin-bottom: 12px;
    }
    .field-wrap input {
        background: #f0f6ff;
    }
    .field-wrap input:focus {
        outline: none;
        border-color: #60a5fa;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.2);
    }
    .error-text {
        margin-top: 6px;
        color: #b91c1c;
        font-size: 14px;
    }
    .login-btn {
        width: 100%;
        margin-top: 8px;
        padding: 11px 16px;
        border-radius: 10px;
    }
</style>

<div class="login-shell">
    <div class="login-card">
        <div class="login-head">
            <h2 class="login-title">Sign in to Your Account</h2>
        </div>

        <div class="login-body">
            <form method="post" action="{{ route('login.submit') }}">
                @csrf

                <div class="field-wrap">
                    <label>Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" required autofocus>
                    @error('email')
                        <div class="error-text">{{ $message }}</div>
                    @enderror
                </div>

                <div class="field-wrap">
                    <label>Password</label>
                    <input type="password" name="password" required>
                    @error('password')
                        <div class="error-text">{{ $message }}</div>
                    @enderror
                </div>

                <button class="btn login-btn" type="submit">masuk</button>
            </form>
        </div>
    </div>
</div>
@endsection
