<?php
namespace App\Http\Controllers;
use App\Models\Deal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class VoteController extends Controller {
    public function store(Request $request,Deal $deal) {
        abort_unless($deal->isPublic(),404);
        $data=$request->validate(['value'=>['required','integer','in:-1,1']]);
        DB::transaction(function () use ($request,$deal,$data) {
            // Serialize votes for this deal to avoid two simultaneous score updates.
            $locked=Deal::query()->lockForUpdate()->findOrFail($deal->id);
            $vote=$locked->votes()->where('user_id',$request->user()->id)->first();
            if ($vote && $vote->value===(int)$data['value']) $vote->delete();
            elseif ($vote) $vote->update(['value'=>(int)$data['value']]);
            else $locked->votes()->create(['user_id'=>$request->user()->id,'value'=>(int)$data['value']]);
            $locked->update(['score'=>(int)$locked->votes()->sum('value')]);
        });
        return back()->with('success','Your vote has been recorded.');
    }
}
