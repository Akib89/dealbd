@extends('layouts.app')
@section('title','Log in')
@section('content')
<div class="auth-layout">
    <section class="auth-intro"><h1>Welcome back to DealBD.</h1><p>Sign in to vote, discuss conditions, save useful offers, and manage your alerts.</p><ul class="auth-benefits"><li>Community context before you buy</li><li>Saved deals and matching alerts</li><li>Transparent merchant disclosure</li></ul></section>
    <section class="auth-card"><h2>Log in</h2><p>Use the email address connected to your account.</p>
    <form method="POST" action="{{ route('login.store') }}" class="stack">@csrf
    <label>Email address<input name="email" type="email" value="{{ old('email') }}" autocomplete="email" required autofocus>@error('email')<span class="field-error">{{ $message }}</span>@enderror</label>
    <label>Password<input name="password" type="password" autocomplete="current-password" required>@error('password')<span class="field-error">{{ $message }}</span>@enderror</label>
    <button class="btn btn-primary block">Log in</button>
    </form><p>New to DealBD? <a href="{{ route('register') }}">Create an account</a></p></section>
</div>
@endsection
