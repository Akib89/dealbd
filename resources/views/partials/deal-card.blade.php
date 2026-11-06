<article class="deal-card">
    <div class="vote-badge" title="Community vote score"><svg aria-hidden="true" viewBox="0 0 20 20"><path d="M5 12.5 10 7l5 5.5"/></svg><strong>{{ $deal->score }}</strong><small>score</small></div>
    <div class="deal-body">
        <div class="deal-meta"><span class="tag">{{ $deal->category->name }}</span><span>{{ $deal->store->name }}</span>@if($deal->featured_at)<span class="tag tag-gold">Editor?s pick</span>@endif</div>
        <h3><a href="{{ route('deals.show',$deal->slug) }}">{{ $deal->title }}</a></h3>
        <p class="snippet">{{ \Illuminate\Support\Str::limit($deal->description,140) }}</p>
        <div class="price-line">
            <span class="price">?{{ number_format((float) $deal->price,2) }}</span>
            @if($deal->original_price)<del>?{{ number_format((float) $deal->original_price,2) }}</del>@endif
            @if($deal->discount_percent)<span class="off">Save {{ $deal->discount_percent }}%</span>@endif
        </div>
        <div class="deal-bottom"><span>Posted by {{ $deal->user->name }} ? {{ $deal->published_at?->diffForHumans() }}</span><span><svg aria-hidden="true" viewBox="0 0 20 20"><path d="M4 4.5h12v8H9l-4 3v-3H4z"/></svg>{{ $deal->comments_count ?? $deal->comments()->count() }} comments</span></div>
    </div>
    <div class="deal-action"><a class="btn btn-outline" href="{{ route('deals.show',$deal->slug) }}">View details</a></div>
</article>
