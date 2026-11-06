<nav class="account-nav" aria-label="Account sections">
    <a @class(['active'=>request()->routeIs('dashboard')]) href="{{ route('dashboard') }}">Overview</a>
    <a @class(['active'=>request()->routeIs('alerts.*')]) href="{{ route('alerts.index') }}">Deal alerts</a>
    <a @class(['active'=>request()->routeIs('notifications.*')]) href="{{ route('notifications.index') }}">Notifications <span class="nav-count">{{ auth()->user()->unreadNotifications()->count() }}</span></a>
    @if(in_array(auth()->user()->role,['merchant','admin'],true))<a @class(['active'=>request()->routeIs('merchant.*')]) href="{{ route('merchant.index') }}">Merchant</a>@endif
    @if(auth()->user()->isStaff())<a @class(['active'=>request()->routeIs('admin.*')]) href="{{ route('admin.index') }}">{{ auth()->user()->isAdmin() ? 'Admin panel' : 'Moderation' }}</a>@endif
</nav>
