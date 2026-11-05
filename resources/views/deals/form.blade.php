@extends('layouts.app')
@section('title',$deal ? 'Edit deal' : 'Submit a deal')
@section('content')
<div class="page-heading"><p class="eyebrow">COMMUNITY SUBMISSION</p><h1>{{ $deal ? 'Edit your deal' : 'Post a new deal' }}</h1><p>Give shoppers enough details to verify the offer. All submissions require moderation.</p></div>
<form class="form-card stack" method="POST" action="{{ $deal ? route('deals.update',$deal->slug) : route('deals.store') }}">
@csrf @if($deal)@method('PUT')@endif
<div class="form-grid">
<label class="wide">Deal title<input name="title" value="{{ old('title',$deal?->title) }}" maxlength="180" required placeholder="E.g. 20% off selected books"></label>
<label>Store<select name="store_id" required><option value="">Choose store</option>@foreach($stores as $store)<option value="{{ $store->id }}" @selected(old('store_id',$deal?->store_id)==$store->id)>{{ $store->name }}</option>@endforeach</select></label>
<label>Category<select name="category_id" required><option value="">Choose category</option>@foreach($categories as $category)<option value="{{ $category->id }}" @selected(old('category_id',$deal?->category_id)==$category->id)>{{ $category->name }}</option>@endforeach</select></label>
<label class="wide">Deal link (http/https)<input type="url" name="deal_url" value="{{ old('deal_url',$deal?->deal_url) }}" required placeholder="https://example.com/product"></label>
<label class="wide">Image URL (optional)<input type="url" name="image_url" value="{{ old('image_url',$deal?->image_url) }}" placeholder="https://example.com/image.jpg"></label>
<label>Deal price (৳)<input type="number" name="price" step="0.01" min="0" value="{{ old('price',$deal?->price) }}" required></label>
<label>Regular price (৳, optional)<input type="number" name="original_price" step="0.01" min="0" value="{{ old('original_price',$deal?->original_price) }}"></label>
<label>Coupon code (optional)<input name="coupon_code" value="{{ old('coupon_code',$deal?->coupon_code) }}"></label>
<label>Payment method (optional)<input name="payment_method" value="{{ old('payment_method',$deal?->payment_method) }}" placeholder="bKash, Nagad, Visa..."></label>
<label>Location (optional)<input name="location" value="{{ old('location',$deal?->location) }}" placeholder="Nationwide, Dhaka, etc."></label>
<label>Expiry date (optional)<input type="datetime-local" name="expires_at" value="{{ old('expires_at',$deal?->expires_at?->timezone(config('dealbd.display_timezone'))->format('Y-m-d\TH:i')) }}"></label>
<label class="wide">Description and important conditions<textarea name="description" rows="8" required placeholder="Minimum spend, code eligibility, delivery charges, redemption steps...">{{ old('description',$deal?->description) }}</textarea></label>
</div>
<label class="check-label"><input type="checkbox" name="affiliate_disclosure" value="1" @checked(old('affiliate_disclosure',$deal?->affiliate_disclosure))> I am affiliated with the merchant or may benefit from this link (disclosure)</label>
<div class="hint">Edits will be resubmitted for approval. Never submit fake or misleading discounts.</div>
<button class="btn btn-primary" type="submit">{{ $deal ? 'Submit changes for review' : 'Submit for review' }}</button>
</form>
@endsection
