<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
class MerchantController extends Controller {
    public function index(Request $request) {
        abort_unless(in_array($request->user()->role,['merchant','admin'],true),403);
        return view('merchant.index',[
            'deals'=>$request->user()->deals()->latest()->paginate(15),
            'totalClicks'=>$request->user()->deals()->sum('clicks'),
            'totalDeals'=>$request->user()->deals()->count(),
        ]);
    }
}
