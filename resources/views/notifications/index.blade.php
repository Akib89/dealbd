@extends('layouts.app')
@section('title','Notifications')
@section('content')
<header class="page-heading"><div><h1>Notifications</h1><p>Newly approved offers that match your saved deal alerts.</p></div></header>
@include('partials.account-nav')
<section class="panel"><div class="panel-heading"><h2>Deal updates</h2><span class="result-count">{{ $items->total() }} total</span></div>@forelse($items as $item)<div @class(['side-link','notification-row','unread'=>!$item->read_at])><div><a href="{{ $item->data['url'] ?? route('home') }}">{{ $item->data['title'] ?? 'A new deal matched your alert' }}</a><p class="hint">{{ $item->created_at->diffForHumans() }} ? {{ $item->read_at ? 'Read' : 'Unread' }}</p></div>@if(!$item->read_at)<form method="POST" action="{{ route('notifications.read',$item->id) }}">@csrf<button class="btn btn-outline compact">Mark read</button></form>@endif</div>@empty<div class="panel-body empty-inline">No notifications yet. Matching approved deals will appear here.</div>@endforelse<div class="panel-body pagination">{{ $items->links() }}</div></section>
@endsection
