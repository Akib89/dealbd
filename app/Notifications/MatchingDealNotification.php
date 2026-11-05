<?php
namespace App\Notifications;
use App\Models\Deal;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
class MatchingDealNotification extends Notification implements ShouldQueue {
    use Queueable;
    public function __construct(public int $dealId, public string $title, public string $slug) { }
    public function via(object $notifiable): array { return ['database']; }
    public function toArray(object $notifiable): array {
        return ['deal_id'=>$this->dealId,'title'=>$this->title,'url'=>route('deals.show',$this->slug)];
    }
}
