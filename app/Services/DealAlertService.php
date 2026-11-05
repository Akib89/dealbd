<?php
namespace App\Services;
use App\Models\{Deal,DealAlert};
use App\Notifications\MatchingDealNotification;
use Illuminate\Support\Str;
class DealAlertService {
    public function dispatchFor(Deal $deal): void {
        DealAlert::query()->with('user')->where('active',true)
            ->where(fn ($q)=>$q->whereNull('category_id')->orWhere('category_id',$deal->category_id))
            ->where(fn ($q)=>$q->whereNull('max_price')->orWhere('max_price','>=',$deal->price))
            ->chunkById(100,function ($alerts) use ($deal) {
                foreach ($alerts as $alert) {
                    if ($alert->keyword && !Str::contains(
                        Str::lower($deal->title.' '.$deal->description),Str::lower($alert->keyword)
                    )) continue;
                    if ($alert->user && (int)$alert->user_id!==(int)$deal->user_id)
                        $alert->user->notify(new MatchingDealNotification($deal->id,$deal->title,$deal->slug));
                }
            });
    }
}
