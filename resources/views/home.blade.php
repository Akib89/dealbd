@extends('layouts.app')
@section('title', 'Deals in Bangladesh')
@section('content')
<section class="discovery-hero" aria-labelledby="discovery-title">
    <div class="discovery-head">
        <div>
            <h1 id="discovery-title">Bangladesh’s deals, checked in public.</h1>
            <p>Search offers, inspect conditions, and let community evidence guide you.</p>
        </div>
        <div class="discovery-actions">
            <a class="btn btn-primary" href="{{ auth()->check() ? route('deals.create') : route('register') }}">Post a deal</a>
            <a class="btn btn-outline" href="#deal-feed">Browse the bulletin</a>
        </div>
    </div>
    <aside class="issue-note" aria-label="Current deal view">
        <strong>{{ number_format($deals->total()) }} {{ \Illuminate\Support\Str::plural('deal', $deals->total()) }}</strong>
        <span>Published in this view. Conditions and community discussion stay visible before you leave for the store.</span>
    </aside>
    <form class="deal-toolbar" method="GET" action="{{ route('home') }}">
        <div class="field search-field"><label for="q">Search deals</label><input id="q" name="q" value="{{ $filters['q'] ?? '' }}" placeholder="Phones, groceries, cashback"></div>
        <div class="field"><label for="category">Category</label><select id="category" name="category"><option value="">All categories</option>@foreach($categories as $category)<option value="{{ $category->id }}" @selected(($filters['category'] ?? '') == $category->id)>{{ $category->name }}</option>@endforeach</select></div>
        <div class="field payment-field"><label for="payment">Payment method</label><input id="payment" name="payment" value="{{ $filters['payment'] ?? '' }}" placeholder="bKash, Nagad"></div>
        <div class="field"><label for="sort">Sort</label><select id="sort" name="sort"><option value="hot" @selected(($filters['sort'] ?? 'hot') === 'hot')>Hot</option><option value="new" @selected(($filters['sort'] ?? '') === 'new')>Newest</option><option value="top" @selected(($filters['sort'] ?? '') === 'top')>Top rated</option></select></div>
        <button class="btn btn-primary search-btn" type="submit">Show deals</button>
    </form>
</section>

@if(!empty($filters['q']) || !empty($filters['category']) || !empty($filters['store']) || !empty($filters['payment']) || (($filters['sort'] ?? 'hot') !== 'hot'))
    <div class="filter-summary"><strong>Filtered view</strong><span>{{ $deals->total() }} matching deals</span><a href="{{ route('home') }}">Clear all filters</a></div>
@endif

<div class="main-grid" id="deal-feed">
    <section aria-labelledby="feed-title">
        <div class="section-header"><h2 id="feed-title">{{ match($filters['sort'] ?? 'hot') { 'new' => 'Newest deals', 'top' => 'Top-rated deals', default => 'Hot deals' } }}</h2><span class="result-count">Updated by the community</span></div>
        <div class="deal-list">
            @forelse($deals as $deal)
                @include('partials.deal-card', ['deal' => $deal])
            @empty
                <div class="empty-state"><h3>No deals match these filters</h3><p>Clear a filter or post a genuine offer for the community to review.</p><div class="form-actions"><a class="btn btn-outline" href="{{ route('home') }}">Clear filters</a><a class="btn btn-primary" href="{{ auth()->check() ? route('deals.create') : route('register') }}">Post a deal</a></div></div>
            @endforelse
        </div>
        <div class="pagination">{{ $deals->links() }}</div>
    </section>
    <aside class="sidebar" aria-label="Browse deals">
        <section class="side-panel"><h2>Browse by category</h2>@foreach($categories as $category)<a class="side-link" href="{{ route('home', ['category' => $category->id]) }}"><span>{{ $category->name }}</span><svg aria-hidden="true" viewBox="0 0 20 20"><path d="M4 10h11m-4-4 4 4-4 4"/></svg></a>@endforeach</section>
        <section class="side-panel"><h2>Browse by store</h2>@foreach($stores as $store)<a class="side-link" href="{{ route('home', ['store' => $store->id]) }}"><span>{{ $store->name }}</span><svg aria-hidden="true" viewBox="0 0 20 20"><path d="M4 10h11m-4-4 4 4-4 4"/></svg></a>@endforeach</section>
        <section class="side-panel side-highlight"><h2>Found a real saving?</h2><p>Share the price and conditions clearly. Votes and discussion help useful deals rise.</p><a class="btn btn-primary block" href="{{ auth()->check() ? route('deals.create') : route('register') }}">Post a deal</a></section>
    </aside>
</div>
@endsection
