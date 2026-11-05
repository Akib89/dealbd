@extends('layouts.app')
@section('title','Merchant analytics')
@section('content')
<div class="page-heading"><p class="eyebrow">MERCHANT OVERVIEW</p><h1>Your promotions</h1><p>Basic analytics for offers posted from your account.</p></div>
<div class="stat-grid"><div class="stat"><strong>{{ number_format($totalDeals) }}</strong><span>Submitted deals</span></div><div class="stat"><strong>{{ number_format($totalClicks) }}</strong><span>Recorded outbound clicks</span></div></div>
<div class="panel"><h2>Your campaigns</h2><div class="table-scroll"><table><thead><tr><th>Deal</th><th>Status</th><th>Score</th><th>Clicks</th></tr></thead><tbody>@foreach($deals as $deal)<tr><td><a href="{{ route('deals.show',$deal->slug) }}">{{ $deal->title }}</a></td><td>{{ $deal->status }}</td><td>{{ $deal->score }}</td><td>{{ $deal->clicks }}</td></tr>@endforeach</tbody></table></div>{{ $deals->links() }}</div>
@endsection
