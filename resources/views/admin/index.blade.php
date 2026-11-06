@extends('layouts.app')
@section('title', auth()->user()->isAdmin() ? 'Admin panel' : 'Moderation center')
@section('content')
<header class="page-heading">
    <div>
        <h1>{{ auth()->user()->isAdmin() ? 'Admin panel' : 'Moderation center' }}</h1>
        <p>{{ auth()->user()->isAdmin() ? 'Manage community accounts, review every posted deal, and keep moderation work moving.' : 'Review community submissions, investigate reports, and protect the public deal feed.' }}</p>
    </div>
</header>
@include('partials.account-nav')

<div class="stat-strip admin-stat-strip">
    <div class="stat"><strong>{{ number_format($pending->total()) }}</strong><span>Pending approvals</span></div>
    <div class="stat"><strong>{{ number_format($openReportCount) }}</strong><span>Open reports</span></div>
    @if(auth()->user()->isAdmin())
        <div class="stat"><strong>{{ number_format($totalDeals) }}</strong><span>Total posts</span></div>
        <div class="stat"><strong>{{ number_format($totalUsers) }}</strong><span>Registered users</span></div>
    @else
        <div class="stat"><strong>Staff</strong><span>Moderator access</span></div>
    @endif
</div>

@if(auth()->user()->isAdmin())
<nav class="admin-section-nav" aria-label="Admin sections">
    <a href="#posts">All posts</a>
    <a href="#users">All users</a>
    <a href="#moderation">Moderation queue</a>
    <a href="#reports">Reports</a>
</nav>

<section class="panel" id="posts">
    <div class="panel-heading"><h2>All posts</h2><span class="result-count">{{ number_format($deals->total()) }} matching posts</span></div>
    <form class="admin-toolbar" method="GET" action="{{ route('admin.index') }}">
        <label>Search posts<input type="search" name="deal_q" value="{{ request('deal_q') }}" placeholder="Title, author, or email"></label>
        <label>Status<select name="deal_status"><option value="">All statuses</option>@foreach(['pending','published','rejected','expired'] as $status)<option value="{{ $status }}" @selected(request('deal_status')===$status)>{{ ucfirst($status) }}</option>@endforeach</select></label>
        <button class="btn btn-primary" type="submit">Find posts</button>
        @if(request()->filled('deal_q') || request()->filled('deal_status'))<a class="btn btn-outline" href="{{ route('admin.index') }}#posts">Clear</a>@endif
    </form>
    <div class="table-scroll"><table>
        <thead><tr><th>Post</th><th>Posted by</th><th>Status</th><th class="numeric">Price</th><th>Updated</th><th>Manage</th></tr></thead>
        <tbody>
        @forelse($deals as $deal)
            <tr>
                <td><a href="{{ route('deals.show', $deal->slug) }}">{{ \Illuminate\Support\Str::limit($deal->title, 58) }}</a><div class="hint">{{ $deal->store->name }}</div></td>
                <td><strong>{{ $deal->user->name }}</strong><div class="hint">{{ $deal->user->email }}</div></td>
                <td><span class="status-tag status-{{ $deal->status }}">{{ ucfirst($deal->status) }}</span></td>
                <td class="numeric">&#2547;{{ number_format((float) $deal->price, 2) }}</td>
                <td>{{ $deal->updated_at->diffForHumans() }}</td>
                <td><a class="btn btn-outline compact" href="{{ route('deals.edit', $deal->slug) }}">Edit post</a></td>
            </tr>
        @empty
            <tr><td colspan="6"><div class="empty-inline">No posts match these filters. Clear the filters to see every post.</div></td></tr>
        @endforelse
        </tbody>
    </table></div>
    @if($deals->hasPages())<div class="panel-body pagination">{{ $deals->links() }}</div>@endif
</section>

<section class="panel" id="users">
    <div class="panel-heading"><h2>All users</h2><span class="result-count">{{ number_format($users->total()) }} matching users</span></div>
    <form class="admin-toolbar" method="GET" action="{{ route('admin.index') }}">
        <label>Search users<input type="search" name="user_q" value="{{ request('user_q') }}" placeholder="Name or email"></label>
        <label>Role<select name="user_role"><option value="">All roles</option>@foreach(['member','merchant','moderator','admin'] as $role)<option value="{{ $role }}" @selected(request('user_role')===$role)>{{ ucfirst($role) }}</option>@endforeach</select></label>
        <button class="btn btn-primary" type="submit">Find users</button>
        @if(request()->filled('user_q') || request()->filled('user_role'))<a class="btn btn-outline" href="{{ route('admin.index') }}#users">Clear</a>@endif
    </form>
    <div class="table-scroll"><table>
        <thead><tr><th>User</th><th>Role</th><th class="numeric">Posts</th><th>Joined</th><th>Manage</th></tr></thead>
        <tbody>
        @forelse($users as $user)
            <tr>
                <td><strong>{{ $user->name }}</strong><div class="hint">{{ $user->email }}</div></td>
                <td><span class="status-tag">{{ ucfirst($user->role) }}</span></td>
                <td class="numeric">{{ number_format($user->deals_count) }}</td>
                <td>{{ $user->created_at->format('d M Y') }}</td>
                <td><a class="btn btn-outline compact" href="{{ route('admin.users.edit', $user) }}">{{ $user->is(auth()->user()) ? 'View account' : 'Edit user' }}</a></td>
            </tr>
        @empty
            <tr><td colspan="5"><div class="empty-inline">No users match these filters. Clear the filters to see every account.</div></td></tr>
        @endforelse
        </tbody>
    </table></div>
    @if($users->hasPages())<div class="panel-body pagination">{{ $users->links() }}</div>@endif
</section>
@endif

<section class="panel" id="moderation">
    <div class="panel-heading"><h2>Pending approvals</h2><span class="result-count">Oldest submissions first</span></div>
    <div class="table-scroll"><table>
        <thead><tr><th>Deal</th><th>Submitted by</th><th class="numeric">Price</th><th>Review</th><th>Decision</th></tr></thead>
        <tbody>
        @forelse($pending as $deal)
            <tr>
                <td><strong>{{ $deal->title }}</strong><div class="hint">{{ $deal->store->name }}</div></td>
                <td>{{ $deal->user->name }}</td>
                <td class="numeric">&#2547;{{ number_format((float) $deal->price, 2) }}</td>
                <td><a href="{{ route('deals.show', $deal->slug) }}">Open submission</a></td>
                <td><div class="action-bar"><form method="POST" action="{{ route('admin.moderate', $deal) }}">@csrf<input type="hidden" name="action" value="approve"><button class="btn btn-primary compact">Approve</button></form><form method="POST" action="{{ route('admin.moderate', $deal) }}">@csrf<input type="hidden" name="action" value="reject"><button class="btn btn-danger compact">Reject</button></form></div></td>
            </tr>
        @empty
            <tr><td colspan="5"><div class="empty-inline">The moderation queue is clear.</div></td></tr>
        @endforelse
        </tbody>
    </table></div>
    @if($pending->hasPages())<div class="panel-body pagination">{{ $pending->links() }}</div>@endif
</section>

<section class="panel" id="reports">
    <div class="panel-heading"><h2>Unresolved reports</h2><span class="result-count">Review the deal before resolving a report</span></div>
    <div class="table-scroll"><table>
        <thead><tr><th>Deal</th><th>Reason</th><th>Reporter notes</th><th>Action</th></tr></thead>
        <tbody>
        @forelse($reports as $report)
            <tr><td><a href="{{ route('deals.show', $report->deal->slug) }}">{{ $report->deal->title }}</a></td><td><span class="status-tag">{{ ucfirst($report->reason) }}</span></td><td>{{ \Illuminate\Support\Str::limit($report->notes ?: 'No additional notes', 100) }}</td><td><form method="POST" action="{{ route('admin.reports.resolve', $report) }}">@csrf<button class="btn btn-outline compact">Mark resolved</button></form></td></tr>
        @empty
            <tr><td colspan="4"><div class="empty-inline">No unresolved reports.</div></td></tr>
        @endforelse
        </tbody>
    </table></div>
</section>
@endsection
