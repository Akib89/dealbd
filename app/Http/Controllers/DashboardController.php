<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
class DashboardController extends Controller {
    public function index(Request $request) {
        $myDeals=$request->user()->deals()->with('store')->latest()->paginate(10);
        $saved=$request->user()->bookmarks()->with('deal.store')->latest()->limit(8)->get();
        return view('dashboard.index',compact('myDeals','saved'));
    }
}
