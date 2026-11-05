<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = ['name', 'email', 'password']; // Never mass-assign role.
    protected $hidden = ['password', 'remember_token'];
    protected function casts(): array { return ['email_verified_at' => 'datetime', 'password' => 'hashed']; }
    public function deals(): HasMany { return $this->hasMany(Deal::class); }
    public function alerts(): HasMany { return $this->hasMany(DealAlert::class); }
    public function bookmarks(): HasMany { return $this->hasMany(Bookmark::class); }
    public function isStaff(): bool { return in_array($this->role, ['admin', 'moderator'], true); }
    public function isAdmin(): bool { return $this->role === 'admin'; }
}
