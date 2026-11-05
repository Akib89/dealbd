<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class PriceHistory extends Model {
    public $timestamps = false;
    protected $fillable = ['deal_id','price','source','recorded_at'];
    protected function casts(): array { return ['price'=>'decimal:2','recorded_at'=>'datetime']; }
}
