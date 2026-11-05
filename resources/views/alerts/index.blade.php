@extends('layouts.app')
@section('title','My deal alerts')
@section('content')
<div class="page-heading"><p class="eyebrow">PERSONALIZED SAVINGS</p><h1>Deal alerts</h1><p>Receive an in-app notification when a newly approved deal matches your conditions.</p></div>
<form class="form-card" method="POST" action="{{ route('alerts.store') }}">@csrf<div class="form-grid"><label>Keyword<input name="keyword" placeholder="e.g. Samsung, earbuds"></label><label>Category<select name="category_id"><option value="">Any category</option>@foreach($categories as $category)<option value="{{ $category->id }}">{{ $category->name }}</option>@endforeach</select></label><label>Maximum price (৳)<input type="number" name="max_price" step="0.01" min="0" placeholder="Optional"></label></div><button class="btn btn-primary">Create alert</button></form>
<div class="panel"><h2>Your alerts</h2>@forelse($alerts as $alert)<div class="side-link"><span>{{ $alert->keyword ?: 'Any keyword' }} · {{ $alert->category?->name ?: 'All categories' }} @if($alert->max_price!==null) · Up to ৳{{ $alert->max_price }}@endif</span><form method="POST" action="{{ route('alerts.destroy',$alert->id) }}">@csrf @method('DELETE')<button class="btn btn-outline compact">Remove</button></form></div>@empty<p class="hint">No active alerts.</p>@endforelse</div>
@endsection
