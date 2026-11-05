<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class Comment extends Model {
    protected $fillable = ['deal_id','user_id','body','status'];
    public function user(): BelongsTo { return $this->belongsTo(User::class); }
}
