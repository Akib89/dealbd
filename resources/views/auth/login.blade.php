@extends('layouts.app')
@section('title','Log in')
@section('content')
<div class="auth-card"><p class="eyebrow">WELCOME BACK</p><h1>Log in</h1><p>Join the conversation and find great deals.</p>
<form method="POST" action="{{ route('login.store') }}" class="stack">@csrf
<label>Email address<input name="email" type="email" value="{{ old('email') }}" autocomplete="email" required></label>
<label>Password<input name="password" type="password" autocomplete="current-password" required></label>
<button class="btn btn-primary block">Log in</button>
</form><p>New here? <a href="{{ route('register') }}">Create an account</a></p></div>
@endsection
