@extends('layouts.app')
@section('title','Merchant analytics')
@section('content')
<header class="page-heading"><div><h1>Merchant overview</h1><p>Review offers submitted from your account and the outbound visits recorded by DealBD.</p></div><a class="btn btn-primary" href="{{ route('deals.create') }}">Submit an offer</a></header>
@include('partials.account-nav')

<div class="stat-strip"><div class="stat"><strong>{{ number_format($totalDeals) }}</strong><span>Submitted deals</span></div><div class="stat"><strong>{{ number_format($totalClicks) }}</strong><span>Recorded outbound visits</span></div><div class="stat"><strong>{{ number_format($deals->total()) }}</strong><span>Campaign records</span></div></div>

<section class="panel"><div class="panel-heading"><h2>Your campaigns</h2><span class="result-count">Visits are not confirmed purchases</span></div><div class="table-scroll"><table><thead><tr><th>Deal</th><th>Status</th><th class="numeric">Score</th><th class="numeric">Outbound visits</th></tr></thead><tbody>@forelse($deals as $deal)<tr><td><a href="{{ route('deals.show',$deal->slug) }}">{{ $deal->title }}</a></td><td><span class="status-tag status-{{ $deal->status }}">{{ ucfirst($deal->status) }}</span></td><td class="numeric">{{ $deal->score }}</td><td class="numeric">{{ number_format($deal->clicks) }}</td></tr>@empty<tr><td colspan="4"><div class="empty-inline">No submitted offers yet. <a href="{{ route('deals.create') }}">Submit your first offer</a>.</div></td></tr>@endforelse</tbody></table></div><div class="panel-body pagination">{{ $deals->links() }}</div></section>
@endsection
