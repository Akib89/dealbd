@extends('layouts.app')
@section('title','Create account')
@section('content')
<div class="auth-card"><p class="eyebrow">JOIN THE COMMUNITY</p><h1>Create account</h1><p>Post deals, vote, save and set alerts.</p>
<form method="POST" action="{{ route('register.store') }}" class="stack">@csrf
<label>Your name<input name="name" value="{{ old('name') }}" autocomplete="name" required></label>
<label>Email address<input name="email" type="email" value="{{ old('email') }}" autocomplete="email" required></label>
<label>Password (minimum 12 characters)<input name="password" type="password" autocomplete="new-password" minlength="12" required></label>
<label>Confirm password<input name="password_confirmation" type="password" autocomplete="new-password" required></label>
<button class="btn btn-primary block">Sign up</button></form></div>
@endsection
