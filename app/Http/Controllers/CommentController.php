<?php
namespace App\Http\Controllers;
use App\Models\Deal;
use Illuminate\Http\Request;
class CommentController extends Controller {
    public function store(Request $request,Deal $deal) {
        abort_unless($deal->isPublic(),404);
        $data=$request->validate(['body'=>['required','string','min:2','max:3000']]);
        $deal->comments()->create(['user_id'=>$request->user()->id,'body'=>$data['body'],'status'=>'published']);
        return back()->with('success','Comment posted.');
    }
}
