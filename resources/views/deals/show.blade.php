@extends('layouts.app')
@section('title',$deal->title)
@section('content')
<nav class="breadcrumbs" aria-label="Breadcrumb"><a href="{{ route('home') }}">Deals</a><span>/</span><a href="{{ route('home',['category'=>$deal->category_id]) }}">{{ $deal->category->name }}</a><span>/</span><span>{{ $deal->store->name }}</span></nav>

@if(!$deal->isPublic())
<div class="state-banner {{ $deal->status }}" role="status"><strong>{{ match($deal->status) { 'pending'=>'Waiting for moderation', 'rejected'=>'This submission was rejected', 'expired'=>'This deal has expired', default=>'This deal is not public' } }}</strong><p>{{ $deal->status==='pending' ? 'Only you and DealBD staff can view it while the details are reviewed.' : 'This page is visible only to the poster and DealBD staff.' }}</p></div>
@endif

<div class="detail-layout">
    <article class="detail-card">
        <div class="deal-meta"><span class="tag">{{ $deal->category->name }}</span><span>{{ $deal->store->name }}</span><span class="status-tag status-{{ $deal->status }}">{{ ucfirst($deal->status) }}</span>@if($deal->featured_at)<span class="tag tag-gold">Editor?s pick</span>@endif</div>
        <h1>{{ $deal->title }}</h1>
        <p class="byline">Posted by {{ $deal->user->name }} ? {{ $deal->created_at->diffForHumans() }}</p>
        @if($deal->image_url)<img class="deal-image" src="{{ $deal->image_url }}" alt="Image submitted for {{ $deal->title }}" referrerpolicy="no-referrer" loading="lazy">@endif

        <div class="price-line large"><span class="price">?{{ number_format((float)$deal->price,2) }}</span>@if($deal->original_price)<del>?{{ number_format((float)$deal->original_price,2) }}</del>@endif @if($deal->discount_percent)<span class="off">Save {{ $deal->discount_percent }}%</span>@endif</div>

        <div class="facts">
          @if($deal->coupon_code)<div><span>Coupon code</span><strong class="code-pill">{{ $deal->coupon_code }}</strong></div>@endif
          @if($deal->payment_method)<div><span>Payment</span><strong>{{ $deal->payment_method }}</strong></div>@endif
          @if($deal->location)<div><span>Location</span><strong>{{ $deal->location }}</strong></div>@endif
          @if($deal->expires_at)<div><span>Expires</span><strong>{{ $deal->expires_at->timezone(config('dealbd.display_timezone'))->format('d M Y, h:i A') }}</strong></div>@endif
          <div><span>Merchant affiliation</span><strong @class(['disclosure-fact'=>!$deal->affiliate_disclosure])>{{ $deal->affiliate_disclosure ? 'Disclosed by poster' : 'No affiliation disclosed' }}</strong></div>
        </div>

        <div class="deal-description">{{ $deal->description }}</div>
        @if($deal->isPublic())<a class="btn btn-primary visit-btn" href="{{ route('deals.visit',$deal->slug) }}" target="_blank" rel="noopener noreferrer nofollow">Visit deal<svg aria-hidden="true" viewBox="0 0 20 20"><path d="M11 4h5v5M16 4l-7 7M8 6H4v10h10v-4"/></svg></a>@endif

        @auth
        @if(auth()->user()->isStaff() && $deal->status==='published')
        <div class="action-bar" aria-label="Staff deal actions">
           <form method="POST" action="{{ route('admin.moderate',$deal->id) }}">@csrf<input type="hidden" name="action" value="{{ $deal->featured_at ? 'unfeature':'feature' }}"><button class="btn btn-outline">{{ $deal->featured_at ? 'Remove editor?s pick' : 'Feature deal' }}</button></form>
           <form method="POST" action="{{ route('admin.moderate',$deal->id) }}">@csrf<input type="hidden" name="action" value="expire"><button class="btn btn-danger">Expire deal</button></form>
        </div>
        @endif
        <div class="action-bar" aria-label="Community deal actions">
          @if($deal->isPublic())
            <form method="POST" action="{{ route('deals.vote',$deal->slug) }}">@csrf<input type="hidden" name="value" value="1"><button class="btn {{ $myVote===1?'btn-primary':'btn-outline' }}">Upvote</button></form>
            <span class="score-display">{{ $deal->score }} points</span>
            <form method="POST" action="{{ route('deals.vote',$deal->slug) }}">@csrf<input type="hidden" name="value" value="-1"><button class="btn {{ $myVote===-1?'btn-primary':'btn-outline' }}">Downvote</button></form>
            <form method="POST" action="{{ route('deals.bookmark',$deal->slug) }}">@csrf<button class="btn btn-outline">{{ $bookmarked ? 'Remove saved deal' : 'Save deal' }}</button></form>
          @endif
          @if(auth()->id()===$deal->user_id || auth()->user()->isStaff())<a class="btn btn-outline" href="{{ route('deals.edit',$deal->slug) }}">Edit deal</a>@endif
        </div>
        @else <p class="hint"><a href="{{ route('login') }}">Log in</a> to vote, comment, save, or report this offer.</p> @endauth
    </article>

    <aside class="sidebar" aria-label="Deal supporting information">
        <section class="side-panel"><h2>Price observations</h2><p>These are user-submitted prices, not independently tracked merchant prices.</p>
            @forelse($history as $point)<div class="side-link"><span>{{ $point->recorded_at->format('d M Y') }}</span><strong>?{{ number_format((float)$point->price,2) }}</strong></div>@empty<p class="empty-inline">No earlier price observations.</p>@endforelse
        </section>
        @auth @if($deal->isPublic())<section class="side-panel"><details class="report-disclosure"><summary>Report a problem with this deal</summary>
            <form method="POST" action="{{ route('deals.report',$deal->slug) }}" class="stack">@csrf
            <label>Reason<select name="reason" required><option value="misleading">Misleading deal</option><option value="expired">Expired offer</option><option value="duplicate">Duplicate</option><option value="spam">Spam</option><option value="other">Other</option></select></label>
            <label>Details<textarea name="notes" rows="3" placeholder="Optional context for moderators"></textarea></label><button class="btn btn-outline">Submit report</button></form></details></section>@endif @endauth
    </aside>
</div>

<section class="comments-section" aria-labelledby="discussion-title">
    <div class="section-header"><h2 id="discussion-title">Discussion</h2><span class="result-count">{{ $comments->total() }} {{ \Illuminate\Support\Str::plural('comment',$comments->total()) }}</span></div>
    @auth @if($deal->isPublic())<form class="form-card stack comment-form" method="POST" action="{{ route('comments.store',$deal->slug) }}">@csrf<label for="comment-body">Add to the discussion<textarea id="comment-body" name="body" rows="4" placeholder="Share eligibility details, redemption tips, or your experience with the offer." required></textarea></label><button class="btn btn-primary" type="submit">Post comment</button></form>@endif @endauth
    @forelse($comments as $comment)<article class="comment"><div class="comment-heading"><strong>{{ $comment->user->name }}</strong><span>{{ $comment->created_at->diffForHumans() }}</span></div><p>{{ $comment->body }}</p>
        @auth @if(auth()->user()->isStaff())<form method="POST" action="{{ route('admin.comments.hide',$comment->id) }}">@csrf<button class="btn btn-text compact">Hide comment</button></form>@endif @endauth
    </article>@empty<div class="empty-state"><h3>No discussion yet</h3><p>Be the first to share a useful question, condition, or verification detail.</p></div>@endforelse
    <div class="pagination">{{ $comments->links() }}</div>
</section>
@endsection
