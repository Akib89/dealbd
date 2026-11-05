<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
class NotificationController extends Controller {
    public function index(Request $request) {
        return view('notifications.index',['items'=>$request->user()->notifications()->latest()->paginate(20)]);
    }
    public function markRead(Request $request,string $id) {
        $notice=$request->user()->notifications()->findOrFail($id);
        $notice->markAsRead();
        return back();
    }
}
