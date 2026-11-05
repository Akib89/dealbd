@extends('layouts.app')
@section('title',$deal->title)
@section('content')
<div class="breadcrumbs"><a href="{{ route('home') }}">Home</a> / <a href="{{ route('home',['category'=>$deal->category_id]) }}">{{ $deal->category->name }}</a> / {{ $deal->store->name }}</div>
<div class="detail-layout">
    <article class="detail-card">
        <div class="deal-meta"><span class="tag">{{ $deal->category->name }}</span><span>{{ $deal->store->name }}</span><span class="tag {{ $deal->status==='published'?'tag-green':'tag-gold' }}">{{ ucfirst($deal->status) }}</span>@if($deal->featured_at)<span class="tag tag-gold">Editor's pick</span>@endif</div>
        <h1>{{ $deal->title }}</h1><p class="byline">Posted by {{ $deal->user->name }} · {{ $deal->created_at->diffForHumans() }}</p>
        @if($deal->image_url)<img class="deal-image" src="{{ $deal->image_url }}" alt="Illustration submitted for deal: {{ $deal->title }}" referrerpolicy="no-referrer" loading="lazy">@endif
        <div class="price-line large"><span class="price">৳{{ number_format((float)$deal->price,2) }}</span>@if($deal->original_price)<del>৳{{ number_format((float)$deal->original_price,2) }}</del>@endif @if($deal->discount_percent)<span class="off">Save {{ $deal->discount_percent }}%</span>@endif</div>
        <div class="facts">
          @if($deal->coupon_code)<div><span>Coupon code</span><strong class="code-pill">{{ $deal->coupon_code }}</strong></div>@endif
          @if($deal->payment_method)<div><span>Payment</span><strong>{{ $deal->payment_method }}</strong></div>@endif
          @if($deal->location)<div><span>Location</span><strong>{{ $deal->location }}</strong></div>@endif
          @if($deal->expires_at)<div><span>Expires</span><strong>{{ $deal->expires_at->timezone(config('dealbd.display_timezone'))->format('d M Y, h:i A') }}</strong></div>@endif
          <div><span>Merchant affiliation</span><strong>{{ $deal->affiliate_disclosure ? 'Disclosed by poster' : 'Not disclosed' }}</strong></div>
        </div>
        <div class="deal-description">{{ $deal->description }}</div>
        @if($deal->isPublic())<a class="btn btn-primary visit-btn" href="{{ route('deals.visit',$deal->slug) }}" target="_blank" rel="noopener noreferrer nofollow">Visit deal ↗</a>@endif
        @auth
        @if(auth()->user()->isStaff() && $deal->status==='published')
        <div class="action-bar">
           <form method="POST" action="{{ route('admin.moderate',$deal->id) }}">@csrf<input type="hidden" name="action" value="{{ $deal->featured_at ? 'unfeature':'feature' }}"><button class="btn btn-outline">{{ $deal->featured_at ? 'Remove from editors picks' : 'Feature on homepage' }}</button></form>
           <form method="POST" action="{{ route('admin.moderate',$deal->id) }}">@csrf<input type="hidden" name="action" value="expire"><button class="btn btn-outline">Expire deal</button></form>
        </div>
        @endif
        <div class="action-bar">
          @if($deal->isPublic())
            <form method="POST" action="{{ route('deals.vote',$deal->slug) }}">@csrf<input type="hidden" name="value" value="1"><button class="btn {{ $myVote===1?'btn-primary':'btn-outline' }}">▲ Upvote</button></form>
            <span class="score-display">{{ $deal->score }} points</span>
            <form method="POST" action="{{ route('deals.vote',$deal->slug) }}">@csrf<input type="hidden" name="value" value="-1"><button class="btn {{ $myVote===-1?'btn-primary':'btn-outline' }}">▼ Downvote</button></form>
            <form method="POST" action="{{ route('deals.bookmark',$deal->slug) }}">@csrf<button class="btn btn-outline">{{ $bookmarked ? '★ Saved' : '☆ Save' }}</button></form>
          @endif
          @if(auth()->id()===$deal->user_id || auth()->user()->isStaff())<a class="btn btn-outline" href="{{ route('deals.edit',$deal->slug) }}">Edit</a>@endif
        </div>
        @else <p class="hint"><a href="{{ route('login') }}">Log in</a> to vote, comment, and save this offer.</p> @endauth
    </article>
    <aside class="sidebar">
        <div class="side-panel"><h3>Price observations</h3><p class="hint">These prices come from user submissions; they are not independently tracked merchant prices.</p>
            @forelse($history as $point)<div class="side-link"><span>{{ $point->recorded_at->format('d M Y') }}</span><strong>৳{{ number_format((float)$point->price,2) }}</strong></div>@empty<p>No history yet.</p>@endforelse
        </div>
        @auth @if($deal->isPublic())<div class="side-panel"><h3>Report a problem</h3>
            <form method="POST" action="{{ route('deals.report',$deal->slug) }}" class="stack">@csrf
            <select name="reason" required><option value="misleading">Misleading deal</option><option value="expired">Expired offer</option><option value="duplicate">Duplicate</option><option value="spam">Spam</option><option value="other">Other</option></select>
            <textarea name="notes" rows="3" placeholder="Optional details"></textarea><button class="btn btn-outline">Submit report</button></form></div>@endif @endauth
    </aside>
</div>
<section class="comments-section"><h2>Discussion ({{ $comments->total() }})</h2>
    @auth @if($deal->isPublic())<form class="form-card stack" method="POST" action="{{ route('comments.store',$deal->slug) }}">@csrf<textarea name="body" rows="4" placeholder="Share a tip, ask about eligibility, or verify the discount..." required></textarea><button class="btn btn-primary" type="submit">Post comment</button></form>@endif @endauth
    @forelse($comments as $comment)<div class="comment"><div class="comment-heading"><strong>{{ $comment->user->name }}</strong><span>{{ $comment->created_at->diffForHumans() }}</span></div><p>{{ $comment->body }}</p>
        @auth @if(auth()->user()->isStaff())<form method="POST" action="{{ route('admin.comments.hide',$comment->id) }}">@csrf<button class="btn btn-text">Hide comment</button></form>@endif @endauth
    </div>@empty<p class="hint">No comments yet.</p>@endforelse
    {{ $comments->links() }}
</section>
@endsection
