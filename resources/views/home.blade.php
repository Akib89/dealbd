@extends('layouts.app')
@section('title','Hot deals in Bangladesh')
@section('content')
<section class="hero">
    <div><p class="eyebrow">COMMUNITY DEAL DISCOVERY</p><h1>Less spending. <span>More saving.</span></h1>
    <p>Real deals posted, discussed and voted on by people in Bangladesh.</p>
    <a class="btn btn-white" href="{{ auth()->check() ? route('deals.create') : route('register') }}">Share a deal →</a></div>
    <div class="hero-side"><span>৳</span><strong>Deals worth sharing.</strong><small>Don't just trust the discount label. Trust the discussion.</small></div>
</section>
<div class="main-grid">
    <div>
        <form class="search-panel" method="GET" action="{{ route('home') }}">
            <div class="field search-field"><label for="q">Search deals</label><input id="q" name="q" value="{{ $filters['q'] ?? '' }}" placeholder="Search phones, groceries, cashback…"></div>
            <div class="field"><label for="category">Category</label><select id="category" name="category"><option value="">All categories</option>@foreach($categories as $category)<option value="{{ $category->id }}" @selected(($filters['category']??'')==$category->id)>{{ $category->name }}</option>@endforeach</select></div>
            <div class="field"><label for="sort">Sort</label><select id="sort" name="sort"><option value="hot" @selected(($filters['sort']??'hot')==='hot')>Hot</option><option value="new" @selected(($filters['sort']??'')==='new')>Newest</option><option value="top" @selected(($filters['sort']??'')==='top')>Top rated</option></select></div>
            <button class="btn btn-primary search-btn" type="submit">Find deals</button>
        </form>
        <div class="section-header"><div><p class="eyebrow muted">LATEST FROM THE COMMUNITY</p><h2>Browse offers</h2></div><span class="result-count">{{ $deals->total() }} deals</span></div>
        <div class="deal-list">@forelse($deals as $deal)@include('partials.deal-card',['deal'=>$deal])@empty<div class="empty-state"><h3>No deals yet</h3><p>Be the first to post a verified offer, or broaden your search.</p></div>@endforelse</div>
        <div class="pagination">{{ $deals->links() }}</div>
    </div>
    <aside class="sidebar">
        <div class="side-panel"><h3>Popular categories</h3>@foreach($categories as $category)<a class="side-link" href="{{ route('home',['category'=>$category->id]) }}"><span>{{ $category->name }}</span><span>→</span></a>@endforeach</div>
        <div class="side-panel"><h3>Browse stores</h3>@foreach($stores as $store)<a class="side-link" href="{{ route('home',['store'=>$store->id]) }}"><span>{{ $store->name }}</span><span>→</span></a>@endforeach</div>
        <div class="side-panel side-highlight"><h3>Know a great deal?</h3><p>Share it with shoppers across Bangladesh. Community votes determine which offers stand out.</p><a class="btn btn-primary block" href="{{ auth()->check()?route('deals.create'):route('register') }}">Post a deal</a></div>
    </aside>
</div>
@endsection
