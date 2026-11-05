<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class DealAlert extends Model {
    protected $fillable = ['user_id','category_id','keyword','max_price','active'];
    protected function casts(): array { return ['max_price'=>'decimal:2','active'=>'boolean']; }
    public function user(): BelongsTo { return $this->belongsTo(User::class); }
    public function category(): BelongsTo { return $this->belongsTo(Category::class); }
}
