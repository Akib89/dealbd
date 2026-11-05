<?php
namespace App\Http\Controllers;
use App\Models\Deal;
use Illuminate\Http\Request;
class ReportController extends Controller {
    public function store(Request $request,Deal $deal) {
        abort_unless($deal->isPublic(),404);
        $data=$request->validate([
            'reason'=>['required','in:expired,misleading,duplicate,spam,other'],
            'notes'=>['nullable','string','max:1000'],
        ]);
        $deal->reports()->updateOrCreate(['user_id'=>$request->user()->id],$data+['resolved'=>false]);
        return back()->with('success','Report submitted for review.');
    }
}
