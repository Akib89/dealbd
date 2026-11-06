@extends('layouts.app')
@section('title','My dashboard')
@section('content')
<header class="page-heading"><div><h1>Your DealBD account</h1><p>Track submitted deals, saved offers, alerts, and community updates.</p></div><a class="btn btn-primary" href="{{ route('deals.create') }}">Post a new deal</a></header>
@include('partials.account-nav')

<section class="panel"><div class="panel-heading"><h2>Submitted deals</h2><span class="result-count">{{ $myDeals->total() }} total</span></div><div class="table-scroll"><table><thead><tr><th>Deal</th><th>Store</th><th>Status</th><th class="numeric">Score</th><th>Action</th></tr></thead><tbody>@forelse($myDeals as $deal)<tr><td><a href="{{ route('deals.show',$deal->slug) }}">{{ $deal->title }}</a></td><td>{{ $deal->store->name }}</td><td><span class="status-tag status-{{ $deal->status }}">{{ ucfirst($deal->status) }}</span></td><td class="numeric">{{ $deal->score }}</td><td><a href="{{ route('deals.edit',$deal->slug) }}">Edit</a></td></tr>@empty<tr><td colspan="5"><div class="empty-inline">You have not submitted a deal yet. <a href="{{ route('deals.create') }}">Post your first deal</a>.</div></td></tr>@endforelse</tbody></table></div><div class="panel-body pagination">{{ $myDeals->links() }}</div></section>

<section class="panel"><div class="panel-heading"><h2>Saved deals</h2><span class="result-count">Up to 8 recent saves</span></div>@forelse($saved as $item)<div class="side-link"><a href="{{ route('deals.show',$item->deal->slug) }}">{{ $item->deal->title }}</a><span>{{ $item->deal->store->name }}</span></div>@empty<div class="panel-body empty-inline">No saved deals yet. Save an offer from its details page to find it here.</div>@endforelse</section>
@endsection
