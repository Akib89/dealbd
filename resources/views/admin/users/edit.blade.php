@extends('layouts.app')
@section('title', 'Edit '.$managedUser->name)
@section('content')
<header class="page-heading">
    <div><h1>Edit user</h1><p>Update account identity and access for {{ $managedUser->name }}.</p></div>
    <a class="btn btn-outline" href="{{ route('admin.index') }}#users">Back to admin panel</a>
</header>
@include('partials.account-nav')

<div class="admin-user-layout">
    <form class="form-card stack" method="POST" action="{{ route('admin.users.update', $managedUser) }}">
        @csrf
        @method('PUT')
        <fieldset class="form-section">
            <legend>Account details</legend>
            <p class="form-section-intro">Changes take effect immediately. Email addresses must remain unique.</p>
            <div class="form-grid">
                <label>Name<input name="name" value="{{ old('name', $managedUser->name) }}" maxlength="100" required>@error('name')<span class="field-error">{{ $message }}</span>@enderror</label>
                <label>Email<input type="email" name="email" value="{{ old('email', $managedUser->email) }}" maxlength="255" required>@error('email')<span class="field-error">{{ $message }}</span>@enderror</label>
                @if($managedUser->is(auth()->user()))
                    <label>Role<input value="{{ ucfirst($managedUser->role) }}" disabled><input type="hidden" name="role" value="{{ $managedUser->role }}"><span class="field-hint">You cannot change your own administrator role.</span></label>
                @else
                    <label>Role<select name="role" required>@foreach(['member','merchant','moderator','admin'] as $role)<option value="{{ $role }}" @selected(old('role', $managedUser->role)===$role)>{{ ucfirst($role) }}</option>@endforeach</select>@error('role')<span class="field-error">{{ $message }}</span>@enderror</label>
                @endif
            </div>
        </fieldset>
        <div class="form-actions"><button class="btn btn-primary" type="submit">Save user</button><span class="hint">This does not change the user's password.</span></div>
    </form>

    <aside class="panel admin-user-summary">
        <div class="panel-heading"><h2>Account summary</h2></div>
        <div class="panel-body">
            <dl class="fact-list"><div><dt>Role</dt><dd>{{ ucfirst($managedUser->role) }}</dd></div><div><dt>Posts</dt><dd>{{ number_format($managedUser->deals_count) }}</dd></div><div><dt>Joined</dt><dd>{{ $managedUser->created_at->format('d M Y') }}</dd></div></dl>
        </div>
    </aside>
</div>

<section class="panel">
    <div class="panel-heading"><h2>Recent posts by this user</h2><span class="result-count">Latest {{ $recentDeals->count() }} shown</span></div>
    <div class="table-scroll"><table>
        <thead><tr><th>Post</th><th>Status</th><th class="numeric">Price</th><th>Updated</th><th>Manage</th></tr></thead>
        <tbody>
        @forelse($recentDeals as $deal)
            <tr><td><a href="{{ route('deals.show', $deal->slug) }}">{{ $deal->title }}</a><div class="hint">{{ $deal->store->name }}</div></td><td><span class="status-tag status-{{ $deal->status }}">{{ ucfirst($deal->status) }}</span></td><td class="numeric">&#2547;{{ number_format((float) $deal->price, 2) }}</td><td>{{ $deal->updated_at->diffForHumans() }}</td><td><a class="btn btn-outline compact" href="{{ route('deals.edit', $deal->slug) }}">Edit post</a></td></tr>
        @empty
            <tr><td colspan="5"><div class="empty-inline">This user has not posted a deal yet.</div></td></tr>
        @endforelse
        </tbody>
    </table></div>
</section>
@endsection
