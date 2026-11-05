<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class DealVote extends Model {
    protected $fillable = ['deal_id','user_id','value'];
    protected function casts(): array { return ['value'=>'integer']; }
}
