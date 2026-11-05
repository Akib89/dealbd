<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#c62534">
    <title>@yield('title', 'Discover real deals') | DealBD</title>
    <meta name="description" content="Discover, discuss and share genuine offers in Bangladesh.">
    <link rel="stylesheet" href="{{ asset('css/dealbd.css') }}">
</head>
<body>
<header class="topbar">
    <div class="shell nav-wrap">
        <a class="brand" href="{{ route('home') }}"><span class="brand-icon">%</span> Deal<span>BD</span></a>
        <nav class="nav-links" aria-label="Main menu">
            <a href="{{ route('home',['sort'=>'hot']) }}">Hot Deals</a>
            <a href="{{ route('home',['sort'=>'new']) }}">New Deals</a>
            <a href="{{ route('home',['sort'=>'top']) }}">Top Rated</a>
            @auth <a href="{{ route('alerts.index') }}">Alerts</a> @endauth
        </nav>
        <div class="nav-actions">
            @auth
                <a href="{{ route('dashboard') }}" class="nav-user">{{ \Illuminate\Support\Str::limit(auth()->user()->name, 15) }}</a>
                <a class="btn btn-light compact" href="{{ route('deals.create') }}">+ Post deal</a>
                <form method="POST" action="{{ route('logout') }}">@csrf<button class="btn btn-text" type="submit">Logout</button></form>
            @else
                <a class="btn btn-text" href="{{ route('login') }}">Log in</a>
                <a class="btn btn-light compact" href="{{ route('register') }}">Sign up</a>
            @endauth
        </div>
    </div>
</header>
<main class="shell content">
    @if(session('success'))<div class="flash success" role="status">{{ session('success') }}</div>@endif
    @if($errors->any())
        <div class="flash error" role="alert"><strong>Please fix:</strong><ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
    @endif
    @yield('content')
</main>
<footer class="site-footer"><div class="shell footer-flex"><span>© {{ date('Y') }} DealBD · Community-led savings in Bangladesh</span><span>Independent community · Affiliate links must be disclosed · Prices in BDT</span></div></footer>
</body>
</html>
