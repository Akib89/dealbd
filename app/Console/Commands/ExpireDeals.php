<?php
namespace App\Console\Commands;
use App\Models\Deal;
use Illuminate\Console\Command;
class ExpireDeals extends Command {
    protected $signature = 'deals:expire';
    protected $description = 'Expire published deals whose end time has passed';
    public function handle(): int {
        $count = Deal::query()->where('status','published')->whereNotNull('expires_at')
            ->where('expires_at','<=',now())->update(['status'=>'expired']);
        $this->info("Expired {$count} deals.");
        return self::SUCCESS;
    }
}
