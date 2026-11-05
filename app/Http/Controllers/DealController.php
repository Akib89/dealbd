<?php
namespace App\Http\Controllers;
use App\Http\Requests\StoreDealRequest;
use App\Models\{Category,Deal,Store};
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use Illuminate\Validation\ValidationException;

class DealController extends Controller {
    public function create() { return view('deals.form', $this->formData()); }

    private function formData(?Deal $deal=null): array {
        return ['deal'=>$deal,'categories'=>Category::orderBy('name')->get(),
            'stores'=>Store::orderBy('name')->get()];
    }

    private function sanitizedData(StoreDealRequest $request): array {
        $data=$request->validated();
        $data['affiliate_disclosure']=$request->boolean('affiliate_disclosure');
        // HTML datetime-local values have no offset; interpret them in Bangladesh time.
        if (!empty($data['expires_at'])) {
            $expiry=Carbon::parse($data['expires_at'],config('dealbd.display_timezone'))->utc();
            if ($expiry->isPast()) {
                throw ValidationException::withMessages(['expires_at'=>'Expiry must be in the future.']);
            }
            $data['expires_at']=$expiry;
        }
        $original=(float)($data['original_price']??0);
        $price=(float)$data['price'];
        $data['discount_percent']=$original>$price && $original>0
            ? min(100,(int) round(100*($original-$price)/$original)) : 0;
        return $data;
    }

    public function store(StoreDealRequest $request) {
        $data=$this->sanitizedData($request);
        $data['user_id']=$request->user()->id;
        $data['slug']=Str::slug($data['title']).'-'.Str::lower(Str::random(7));
        $data['status']='pending';
        $deal=Deal::create($data);
        $deal->priceHistory()->create(['price'=>$deal->price,'source'=>'user_submitted','recorded_at'=>now()]);
        return redirect()->route('deals.show',$deal->slug)->with('success','Deal submitted for moderation.');
    }

    public function show(Request $request, Deal $deal) {
        $canReview=$request->user() && ($request->user()->id===$deal->user_id || $request->user()->isStaff());
        abort_unless($deal->isPublic() || $canReview,404);
        $deal->load(['user','category','store']);
        $comments=$deal->comments()->with('user:id,name')->latest()->paginate(12);
        $history=$deal->priceHistory()->latest('recorded_at')->limit(12)->get();
        $myVote=$request->user() ? $deal->votes()->where('user_id',$request->user()->id)->value('value'):null;
        $bookmarked=$request->user() ? $deal->bookmarks()->where('user_id',$request->user()->id)->exists():false;
        return view('deals.show',compact('deal','comments','history','myVote','bookmarked'));
    }

    public function edit(Request $request,Deal $deal) {
        abort_unless($request->user()->id===$deal->user_id || $request->user()->isStaff(),403);
        return view('deals.form',$this->formData($deal));
    }

    public function update(StoreDealRequest $request,Deal $deal) {
        abort_unless($request->user()->id===$deal->user_id || $request->user()->isStaff(),403);
        $data=$this->sanitizedData($request);
        $priceChanged=(float)$deal->price!==(float)$data['price'];
        // Significant edits require staff review again; a featured deal is unfeatured.
        $data['status']='pending';
        $data['featured_at']=null;
        $data['published_at']=null;
        $deal->update($data);
        if ($priceChanged) $deal->priceHistory()->create([
            'price'=>$deal->price,'source'=>'user_submitted','recorded_at'=>now()
        ]);
        return redirect()->route('deals.show',$deal->slug)->with('success','Changes sent for moderation.');
    }
}
