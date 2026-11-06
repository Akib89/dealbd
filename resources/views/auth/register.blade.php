@extends('layouts.app')
@section('title','Create account')
@section('content')
<div class="auth-layout">
    <section class="auth-intro"><h1>Help Bangladesh find better deals.</h1><p>Create an account to contribute offers and add the community context that discount labels cannot provide.</p><ul class="auth-benefits"><li>Post deals for moderation</li><li>Vote, comment, save, and report</li><li>Create up to 20 matching alerts</li></ul></section>
    <section class="auth-card"><h2>Create account</h2><p>Your submissions and comments will use this community name.</p>
    <form method="POST" action="{{ route('register.store') }}" class="stack">@csrf
    <label>Your name<input name="name" value="{{ old('name') }}" autocomplete="name" required autofocus>@error('name')<span class="field-error">{{ $message }}</span>@enderror</label>
    <label>Email address<input name="email" type="email" value="{{ old('email') }}" autocomplete="email" required>@error('email')<span class="field-error">{{ $message }}</span>@enderror</label>
    <label>Password <span class="field-hint">Minimum 12 characters</span><input name="password" type="password" autocomplete="new-password" minlength="12" required>@error('password')<span class="field-error">{{ $message }}</span>@enderror</label>
    <label>Confirm password<input name="password_confirmation" type="password" autocomplete="new-password" required></label>
    <button class="btn btn-primary block">Create account</button></form>
    <p>Already registered? <a href="{{ route('login') }}">Log in</a></p></section>
</div>
@endsection
