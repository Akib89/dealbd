<?php
namespace App\Http\Controllers;
use App\Models\Deal;
use Illuminate\Http\Request;
class BookmarkController extends Controller {
    public function toggle(Request $request,Deal $deal) {
        abort_unless($deal->isPublic(),404);
        $bookmark=$deal->bookmarks()->where('user_id',$request->user()->id)->first();
        if($bookmark) $bookmark->delete();
        else $deal->bookmarks()->firstOrCreate(['user_id'=>$request->user()->id]);
        return back()->with('success',$bookmark?'Bookmark removed.':'Deal saved.');
    }
}
