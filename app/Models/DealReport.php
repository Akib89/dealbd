<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class DealReport extends Model {
    protected $fillable = ['deal_id','user_id','reason','notes','resolved'];
    protected function casts(): array { return ['resolved'=>'boolean']; }
    public function deal(): BelongsTo { return $this->belongsTo(Deal::class); }
    public function user(): BelongsTo { return $this->belongsTo(User::class); }
}
