<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#e82e31">
    <title>@yield('title', 'Discover real deals') | DealBD</title>
    <meta name="description" content="Discover, discuss and share genuine offers in Bangladesh.">
    <link rel="stylesheet" href="{{ asset('css/dealbd.css') }}">
</head>
<body>
<a class="skip-link" href="#main-content">Skip to main content</a>
<header class="topbar">
    <div class="shell nav-wrap">
        <a class="brand" href="{{ route('home') }}" aria-label="DealBD home"><span class="brand-icon" aria-hidden="true">%</span><span class="brand-word">Deal<strong>BD</strong></span></a>
        <nav class="nav-links" aria-label="Main menu">
            <a @class(['active'=>request()->routeIs('home') && request('sort','hot')==='hot']) href="{{ route('home',['sort'=>'hot']) }}">Hot deals</a>
            <a @class(['active'=>request()->routeIs('home') && request('sort')==='new']) href="{{ route('home',['sort'=>'new']) }}">New deals</a>
            <a @class(['active'=>request()->routeIs('home') && request('sort')==='top']) href="{{ route('home',['sort'=>'top']) }}">Top rated</a>
            @auth <a @class(['active'=>request()->routeIs('alerts.*')]) href="{{ route('alerts.index') }}">Alerts</a> @endauth
        </nav>
        <div class="nav-actions">
            @auth
                <a href="{{ route('dashboard') }}" class="nav-user">{{ \Illuminate\Support\Str::limit(auth()->user()->name, 15) }}</a>
                <a class="btn btn-light compact" href="{{ route('deals.create') }}">Post a deal</a>
                <form method="POST" action="{{ route('logout') }}">@csrf<button class="btn btn-text" type="submit">Logout</button></form>
            @else
                <a class="btn btn-text" href="{{ route('login') }}">Log in</a>
                <a class="btn btn-light compact" href="{{ route('register') }}">Sign up</a>
            @endauth
        </div>
    </div>
</header>
<main id="main-content" class="shell content" tabindex="-1">
    @if(session('success'))<div class="flash success" role="status"><strong>Done</strong><span>{{ session('success') }}</span></div>@endif
    @if($errors->any())
        <div class="flash error" role="alert"><strong>Please check the highlighted information</strong><ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
    @endif
    @yield('content')
</main>
<footer class="site-footer"><div class="shell footer-flex"><strong>DealBD</strong><span>Community-led savings in Bangladesh</span><span>Independent community ? Affiliate links must be disclosed ? Prices in BDT</span><span>? {{ date('Y') }}</span></div></footer>
</body>
</html>
