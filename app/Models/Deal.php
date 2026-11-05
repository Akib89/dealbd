<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Deal extends Model
{
    protected $fillable = ['user_id','category_id','store_id','title','slug','description','deal_url','image_url',
        'price','original_price','discount_percent','coupon_code','payment_method','location',
        'affiliate_disclosure','expires_at','status','published_at','featured_at'];

    protected function casts(): array {
        return ['price'=>'decimal:2','original_price'=>'decimal:2','expires_at'=>'datetime',
            'published_at'=>'datetime','featured_at'=>'datetime','affiliate_disclosure'=>'boolean'];
    }
    public function user(): BelongsTo { return $this->belongsTo(User::class); }
    public function category(): BelongsTo { return $this->belongsTo(Category::class); }
    public function store(): BelongsTo { return $this->belongsTo(Store::class); }
    public function votes(): HasMany { return $this->hasMany(DealVote::class); }
    public function comments(): HasMany { return $this->hasMany(Comment::class)->where('status','published'); }
    public function bookmarks(): HasMany { return $this->hasMany(Bookmark::class); }
    public function reports(): HasMany { return $this->hasMany(DealReport::class); }
    public function priceHistory(): HasMany { return $this->hasMany(PriceHistory::class); }
    public function isPublic(): bool { return $this->status === 'published' && (!$this->expires_at || $this->expires_at->isFuture()); }
}
