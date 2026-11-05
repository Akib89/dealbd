<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\{Comment, Deal, DealReport, User};
use App\Services\DealAlertService;
use Illuminate\Http\Request;
class ModerationController extends Controller {
    public function index() {
        return view('admin.index',[
            'pending'=>Deal::with(['store','user'])->where('status','pending')->oldest()->paginate(20),
            'reports'=>DealReport::with(['deal','user'])->where('resolved',false)->latest()->limit(20)->get(),
            'users'=>User::latest()->limit(20)->get(),
        ]);
    }
    public function moderate(Request $request,Deal $deal,DealAlertService $alertService) {
        $action=$request->validate(['action'=>['required','in:approve,reject,feature,unfeature,expire']])['action'];
        $wasPublished=$deal->status==='published';
        switch($action) {
            case 'approve':
                abort_unless($deal->status==='pending',422);
                $deal->update(['status'=>'published','published_at'=>now()]);
                $alertService->dispatchFor($deal);
                break;
            case 'reject':
                $deal->update(['status'=>'rejected','featured_at'=>null]);
                break;
            case 'feature':
                abort_unless($wasPublished,422);
                $deal->update(['featured_at'=>now()]);
                break;
            case 'unfeature':
                $deal->update(['featured_at'=>null]);
                break;
            case 'expire':
                $deal->update(['status'=>'expired','featured_at'=>null]);
                break;
        }
        return back()->with('success','Moderation action completed.');
    }
    public function resolveReport(DealReport $report) {
        $report->update(['resolved'=>true]);
        return back()->with('success','Report resolved.');
    }
    public function hideComment(Comment $comment) {
        $comment->update(['status'=>'hidden']);
        return back()->with('success','Comment hidden.');
    }
    public function setRole(Request $request,User $user) {
        $data=$request->validate(['role'=>['required','in:member,merchant,moderator,admin']]);
        abort_if($request->user()->id===$user->id,403,'You cannot change your own role.');
        $user->forceFill(['role'=>$data['role']])->save();
        return back()->with('success','User role updated.');
    }
}
