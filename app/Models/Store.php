<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
class Store extends Model {
    protected $fillable = ['name', 'slug', 'website'];
    public function deals(): HasMany { return $this->hasMany(Deal::class); }
}
