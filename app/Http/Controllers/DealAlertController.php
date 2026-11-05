<?php
namespace App\Http\Controllers;
use App\Models\{Category, DealAlert};
use Illuminate\Http\Request;
class DealAlertController extends Controller {
    public function index(Request $request) {
        return view('alerts.index',[
            'alerts'=>$request->user()->alerts()->with('category')->latest()->get(),
            'categories'=>Category::orderBy('name')->get(),
        ]);
    }
    public function store(Request $request) {
        $data=$request->validate([
            'keyword'=>['nullable','string','max:100'],
            'category_id'=>['nullable','exists:categories,id'],
            'max_price'=>['nullable','numeric','min:0'],
        ]);
        if(empty($data['keyword']) && empty($data['category_id']) && (!isset($data['max_price']) || $data['max_price']===''))
            return back()->withErrors(['keyword'=>'Select at least one alert condition.']);
        if($request->user()->alerts()->count()>=20)
            return back()->withErrors(['keyword'=>'Limit reached: up to 20 alerts per account.']);
        $request->user()->alerts()->create($data+['active'=>true]);
        return back()->with('success','Alert created.');
    }
    public function destroy(Request $request,DealAlert $alert) {
        abort_unless($alert->user_id===$request->user()->id,403);
        $alert->delete();
        return back()->with('success','Alert removed.');
    }
}
