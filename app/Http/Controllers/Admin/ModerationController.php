<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateUserRequest;
use App\Models\Comment;
use App\Models\Deal;
use App\Models\DealReport;
use App\Models\User;
use App\Services\DealAlertService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ModerationController extends Controller
{
    public function index(Request $request): View
    {
        $pending = Deal::with(['store', 'user'])
            ->where('status', 'pending')
            ->oldest()
            ->paginate(15, ['*'], 'pending_page')
            ->withQueryString();

        $reports = DealReport::with(['deal', 'user'])
            ->where('resolved', false)
            ->latest()
            ->limit(20)
            ->get();

        $users = null;
        $deals = null;

        if ($request->user()->isAdmin()) {
            $userSearch = trim((string) $request->query('user_q'));
            $userRole = (string) $request->query('user_role');
            $dealSearch = trim((string) $request->query('deal_q'));
            $dealStatus = (string) $request->query('deal_status');

            $allowedRoles = ['member', 'merchant', 'moderator', 'admin'];
            $allowedStatuses = ['pending', 'published', 'rejected', 'expired'];

            $users = User::query()
                ->withCount('deals')
                ->when($userSearch !== '', function (Builder $query) use ($userSearch): void {
                    $query->where(function (Builder $query) use ($userSearch): void {
                        $query->where('name', 'like', '%'.$userSearch.'%')
                            ->orWhere('email', 'like', '%'.$userSearch.'%');
                    });
                })
                ->when(in_array($userRole, $allowedRoles, true), fn (Builder $query) => $query->where('role', $userRole))
                ->latest()
                ->paginate(20, ['*'], 'users_page')
                ->withQueryString();

            $deals = Deal::query()
                ->with(['store:id,name', 'user:id,name,email'])
                ->when($dealSearch !== '', function (Builder $query) use ($dealSearch): void {
                    $query->where(function (Builder $query) use ($dealSearch): void {
                        $query->where('title', 'like', '%'.$dealSearch.'%')
                            ->orWhereHas('user', fn (Builder $userQuery) => $userQuery
                                ->where('name', 'like', '%'.$dealSearch.'%')
                                ->orWhere('email', 'like', '%'.$dealSearch.'%'));
                    });
                })
                ->when(in_array($dealStatus, $allowedStatuses, true), fn (Builder $query) => $query->where('status', $dealStatus))
                ->latest('updated_at')
                ->paginate(20, ['*'], 'deals_page')
                ->withQueryString();
        }

        return view('admin.index', [
            'pending' => $pending,
            'reports' => $reports,
            'users' => $users,
            'deals' => $deals,
            'totalUsers' => $request->user()->isAdmin() ? User::count() : null,
            'totalDeals' => $request->user()->isAdmin() ? Deal::count() : null,
            'openReportCount' => DealReport::where('resolved', false)->count(),
        ]);
    }

    public function editUser(User $user): View
    {
        $user->loadCount('deals');

        return view('admin.users.edit', [
            'managedUser' => $user,
            'recentDeals' => $user->deals()->with('store:id,name')->latest()->limit(10)->get(),
        ]);
    }

    public function updateUser(UpdateUserRequest $request, User $user): RedirectResponse
    {
        $data = $request->validated();

        abort_if(
            $request->user()->is($user) && $data['role'] !== $user->role,
            403,
            'You cannot change your own role.',
        );

        $user->forceFill($data)->save();

        return redirect()->route('admin.users.edit', $user)->with('success', 'User account updated.');
    }

    public function moderate(Request $request, Deal $deal, DealAlertService $alertService): RedirectResponse
    {
        $action = $request->validate(['action' => ['required', 'in:approve,reject,feature,unfeature,expire']])['action'];
        $wasPublished = $deal->status === 'published';

        switch ($action) {
            case 'approve':
                abort_unless($deal->status === 'pending', 422);
                $deal->update(['status' => 'published', 'published_at' => now()]);
                $alertService->dispatchFor($deal);
                break;
            case 'reject':
                $deal->update(['status' => 'rejected', 'featured_at' => null]);
                break;
            case 'feature':
                abort_unless($wasPublished, 422);
                $deal->update(['featured_at' => now()]);
                break;
            case 'unfeature':
                $deal->update(['featured_at' => null]);
                break;
            case 'expire':
                $deal->update(['status' => 'expired', 'featured_at' => null]);
                break;
        }

        return back()->with('success', 'Moderation action completed.');
    }

    public function resolveReport(DealReport $report): RedirectResponse
    {
        $report->update(['resolved' => true]);

        return back()->with('success', 'Report resolved.');
    }

    public function hideComment(Comment $comment): RedirectResponse
    {
        $comment->update(['status' => 'hidden']);

        return back()->with('success', 'Comment hidden.');
    }

    public function setRole(Request $request, User $user): RedirectResponse
    {
        $data = $request->validate(['role' => ['required', 'in:member,merchant,moderator,admin']]);
        abort_if($request->user()->is($user), 403, 'You cannot change your own role.');
        $user->forceFill(['role' => $data['role']])->save();

        return back()->with('success', 'User role updated.');
    }
}
