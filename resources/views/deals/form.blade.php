@extends('layouts.app')
@section('title',$deal ? 'Edit deal' : 'Submit a deal')
@section('content')
<header class="page-heading"><div><h1>{{ $deal ? 'Edit deal' : 'Post a new deal' }}</h1><p>Give the community enough detail to understand the saving and verify the conditions. Every submission is reviewed before publication.</p></div></header>

<form class="form-card stack" method="POST" action="{{ $deal ? route('deals.update',$deal->slug) : route('deals.store') }}">
@csrf @if($deal)@method('PUT')@endif
<fieldset class="form-section"><legend>Deal basics</legend><p class="form-section-intro">Identify the offer and where shoppers can find it.</p><div class="form-grid">
<label class="wide">Deal title<input name="title" value="{{ old('title',$deal?->title) }}" maxlength="180" required placeholder="Example: 20% off selected books">@error('title')<span class="field-error">{{ $message }}</span>@enderror</label>
<label>Store<select name="store_id" required><option value="">Choose store</option>@foreach($stores as $store)<option value="{{ $store->id }}" @selected(old('store_id',$deal?->store_id)==$store->id)>{{ $store->name }}</option>@endforeach</select>@error('store_id')<span class="field-error">{{ $message }}</span>@enderror</label>
<label>Category<select name="category_id" required><option value="">Choose category</option>@foreach($categories as $category)<option value="{{ $category->id }}" @selected(old('category_id',$deal?->category_id)==$category->id)>{{ $category->name }}</option>@endforeach</select>@error('category_id')<span class="field-error">{{ $message }}</span>@enderror</label>
<label class="wide">Deal link<input type="url" name="deal_url" value="{{ old('deal_url',$deal?->deal_url) }}" required placeholder="https://example.com/product">@error('deal_url')<span class="field-error">{{ $message }}</span>@enderror</label>
<label class="wide">Image URL <span class="field-hint">Optional</span><input type="url" name="image_url" value="{{ old('image_url',$deal?->image_url) }}" placeholder="https://example.com/image.jpg">@error('image_url')<span class="field-error">{{ $message }}</span>@enderror</label>
</div></fieldset>

<fieldset class="form-section"><legend>Price and saving</legend><p class="form-section-intro">Enter the current deal price. A regular price is only useful when it is genuine and comparable.</p><div class="form-grid">
<label>Deal price (?)<input type="number" name="price" step="0.01" min="0" value="{{ old('price',$deal?->price) }}" required>@error('price')<span class="field-error">{{ $message }}</span>@enderror</label>
<label>Regular price (?) <span class="field-hint">Optional</span><input type="number" name="original_price" step="0.01" min="0" value="{{ old('original_price',$deal?->original_price) }}">@error('original_price')<span class="field-error">{{ $message }}</span>@enderror</label>
</div></fieldset>

<fieldset class="form-section"><legend>Redemption details</legend><p class="form-section-intro">Add the conditions shoppers need before they try to claim the offer.</p><div class="form-grid">
<label>Coupon code <span class="field-hint">Optional</span><input name="coupon_code" value="{{ old('coupon_code',$deal?->coupon_code) }}">@error('coupon_code')<span class="field-error">{{ $message }}</span>@enderror</label>
<label>Payment method <span class="field-hint">Optional</span><input name="payment_method" value="{{ old('payment_method',$deal?->payment_method) }}" placeholder="bKash, Nagad, Visa...">@error('payment_method')<span class="field-error">{{ $message }}</span>@enderror</label>
<label>Location <span class="field-hint">Optional</span><input name="location" value="{{ old('location',$deal?->location) }}" placeholder="Nationwide, Dhaka, etc.">@error('location')<span class="field-error">{{ $message }}</span>@enderror</label>
<label>Expiry date <span class="field-hint">Optional ? Bangladesh time</span><input type="datetime-local" name="expires_at" value="{{ old('expires_at',$deal?->expires_at?->timezone(config('dealbd.display_timezone'))->format('Y-m-d\TH:i')) }}">@error('expires_at')<span class="field-error">{{ $message }}</span>@enderror</label>
</div></fieldset>

<fieldset class="form-section"><legend>Description and disclosure</legend><div class="form-grid">
<label class="wide">Important conditions<textarea name="description" rows="8" required placeholder="Minimum spend, eligibility, delivery charges, redemption steps, exclusions...">{{ old('description',$deal?->description) }}</textarea>@error('description')<span class="field-error">{{ $message }}</span>@enderror</label>
</div>
<label class="check-label"><input type="checkbox" name="affiliate_disclosure" value="1" @checked(old('affiliate_disclosure',$deal?->affiliate_disclosure))><span>I am affiliated with the merchant or may benefit from this link. This disclosure will be shown on the deal.</span></label>
</fieldset>

<div class="form-actions"><button class="btn btn-primary" type="submit">{{ $deal && auth()->user()->isStaff() ? 'Save post' : ($deal ? 'Submit changes for review' : 'Submit deal for review') }}</button><span class="hint">{{ $deal && auth()->user()->isStaff() ? 'Staff edits keep the current moderation status.' : ($deal ? 'Significant edits return the deal to moderation.' : 'The deal will not be public until approved.') }}</span></div>
</form>
@endsection
