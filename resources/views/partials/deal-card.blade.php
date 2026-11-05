<article class="deal-card">
    <div class="vote-badge" title="Community vote score"><span>▲</span><strong>{{ $deal->score }}</strong><small>votes</small></div>
    <div class="deal-body">
        <div class="deal-meta"><span class="tag">{{ $deal->category->name }}</span><span>{{ $deal->store->name }}</span>@if($deal->featured_at)<span class="tag tag-gold">Editor's pick</span>@endif</div>
        <h3><a href="{{ route('deals.show',$deal->slug) }}">{{ $deal->title }}</a></h3>
        <p class="snippet">{{ \Illuminate\Support\Str::limit($deal->description,140) }}</p>
        <div class="price-line">
            <span class="price">৳{{ number_format((float) $deal->price,2) }}</span>
            @if($deal->original_price)<del>৳{{ number_format((float) $deal->original_price,2) }}</del>@endif
            @if($deal->discount_percent)<span class="off">{{ $deal->discount_percent }}% OFF</span>@endif
        </div>
        <div class="deal-bottom"><span>By {{ $deal->user->name }} · {{ $deal->published_at?->diffForHumans() }}</span><span>▤ {{ $deal->comments_count ?? $deal->comments()->count() }} comments</span></div>
    </div>
    <div class="deal-action"><a class="btn btn-outline" href="{{ route('deals.show',$deal->slug) }}">View deal</a></div>
</article>
