<?php
namespace App\Http\Controllers;
use App\Models\{Category, Deal, Store};
use Illuminate\Http\Request;
class HomeController extends Controller {
    public function index(Request $request) {
        $filters=$request->validate([
            'q'=>['nullable','string','max:100'],
            'category'=>['nullable','integer','exists:categories,id'],
            'store'=>['nullable','integer','exists:stores,id'],
            'sort'=>['nullable','in:hot,new,top'],
            'payment'=>['nullable','string','max:100'],
        ]);
        $deals=Deal::query()->with(['user:id,name','category:id,name','store:id,name'])
            ->withCount('comments')->where('status','published')
            ->where(fn ($q)=>$q->whereNull('expires_at')->orWhere('expires_at','>',now()));
        if ($q=trim($filters['q']??'')) {
            $deals->where(fn ($query)=>$query->where('title','like',"%{$q}%")->orWhere('description','like',"%{$q}%"));
        }
        if (!empty($filters['category'])) $deals->where('category_id',$filters['category']);
        if (!empty($filters['store'])) $deals->where('store_id',$filters['store']);
        if (!empty($filters['payment'])) $deals->where('payment_method','like','%'.$filters['payment'].'%');
        match ($filters['sort']??'hot') {
            'new'=>$deals->orderByDesc('published_at'),
            'top'=>$deals->orderByDesc('score')->orderByDesc('published_at'),
            default=>$deals->orderByDesc('featured_at')->orderByDesc('score')->orderByDesc('published_at'),
        };
        return view('home',[
            'deals'=>$deals->paginate(12)->withQueryString(),
            'categories'=>Category::orderBy('name')->get(),
            'stores'=>Store::orderBy('name')->get(),
            'filters'=>$filters,
        ]);
    }
}
