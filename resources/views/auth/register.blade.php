@extends('layouts.app')

@section('title', 'Register')

@section('content')
<div class="container" style="max-width: 400px; margin-top: 40px;">
    <div class="card">
        <h2 style="margin-bottom: 1rem;">Register</h2>
        <form method="POST" action="{{ route('register') }}">
            @csrf

            <div>
                <label for="name">Name</label>
                <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name">
            </div>

            <div>
                <label for="email">Email</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="username">
            </div>

            <div>
                <label for="password">Password</label>
                <input id="password" type="password" name="password" required autocomplete="new-password">
            </div>

            <div>
                <label for="password_confirmation">Confirm Password</label>
                <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password">
            </div>

            <button type="submit" class="btn" style="width: 100%; text-align:center;">
                Register
            </button>
        </form>

        <p style="margin-top:1rem;font-size:0.9rem;">
            Sudah punya akun?
            <a href="{{ route('login') }}">Login</a>
        </p>
    </div>
</div>
@endsection