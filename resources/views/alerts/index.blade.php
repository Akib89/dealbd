@extends('layouts.app')
@section('title','My deal alerts')
@section('content')
<header class="page-heading"><div><h1>Deal alerts</h1><p>Receive an in-app notification when a newly approved deal matches at least one alert you create.</p></div></header>
@include('partials.account-nav')

<form class="form-card stack" method="POST" action="{{ route('alerts.store') }}">@csrf
<fieldset class="form-section"><legend>Create an alert</legend><p class="form-section-intro">Choose at least one condition. Each account can keep up to 20 active alerts.</p><div class="form-grid"><label>Keyword<input name="keyword" value="{{ old('keyword') }}" placeholder="Samsung, earbuds...">@error('keyword')<span class="field-error">{{ $message }}</span>@enderror</label><label>Category<select name="category_id"><option value="">Any category</option>@foreach($categories as $category)<option value="{{ $category->id }}" @selected(old('category_id')==$category->id)>{{ $category->name }}</option>@endforeach</select>@error('category_id')<span class="field-error">{{ $message }}</span>@enderror</label><label>Maximum price (?)<input type="number" name="max_price" value="{{ old('max_price') }}" step="0.01" min="0" placeholder="Optional">@error('max_price')<span class="field-error">{{ $message }}</span>@enderror</label></div></fieldset><button class="btn btn-primary">Create alert</button></form>

<section class="panel"><div class="panel-heading"><h2>Active alerts</h2><span class="result-count">{{ $alerts->count() }} of 20</span></div>@forelse($alerts as $alert)<div class="side-link"><span><strong>{{ $alert->keyword ?: 'Any keyword' }}</strong><br><span class="hint">{{ $alert->category?->name ?: 'All categories' }}@if($alert->max_price!==null) ? Up to ?{{ number_format((float)$alert->max_price,2) }}@endif</span></span><form method="POST" action="{{ route('alerts.destroy',$alert->id) }}">@csrf @method('DELETE')<button class="btn btn-danger compact">Remove</button></form></div>@empty<div class="panel-body empty-inline">No active alerts. Create one above to receive matching in-app notifications.</div>@endforelse</section>
@endsection
