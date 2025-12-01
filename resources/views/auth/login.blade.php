@extends('layouts.app')

@section('title', 'Login')

@section('content')
<div class="container" style="max-width: 400px; margin-top: 40px;">
    <div class="card">
        <h2 style="margin-bottom: 1rem;">Login</h2>
        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div>
                <label for="email">Email</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username">
            </div>

            <div>
                <label for="password">Password</label>
                <input id="password" type="password" name="password" required autocomplete="current-password">
            </div>

            <div style="margin: 0.5rem 0;">
                <label style="display:flex;align-items:center;gap:6px;">
                    <input type="checkbox" name="remember" style="width:auto;">
                    <span>Remember me</span>
                </label>
            </div>

            <button type="submit" class="btn" style="width: 100%; text-align:center;">
                Login
            </button>
        </form>

        <p style="margin-top:1rem;font-size:0.9rem;">
            Belum punya akun?
            <a href="{{ route('register') }}">Register</a>
        </p>
    </div>
</div>
@endsection
