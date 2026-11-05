@extends('layouts.app')
@section('title','Notifications')
@section('content')
<div class="page-heading"><p class="eyebrow">DEAL UPDATES</p><h1>Notifications</h1><p>New offers matching your saved alerts.</p></div>
<div class="panel">@forelse($items as $item)<div class="side-link"><div><a href="{{ $item->data['url'] ?? route('home') }}">{{ $item->data['title'] ?? 'A new deal matched your alert' }}</a><p class="hint">{{ $item->created_at->diffForHumans() }} · {{ $item->read_at ? 'Read' : 'Unread' }}</p></div>@if(!$item->read_at)<form method="POST" action="{{ route('notifications.read',$item->id) }}">@csrf<button class="btn btn-outline compact">Mark read</button></form>@endif</div>@empty<p>No notifications yet.</p>@endforelse{{ $items->links() }}</div>
@endsection
