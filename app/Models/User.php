<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Event; 
use Illuminate\Support\Facades\DB;


class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    public function isAdmin()
{
    return $this->is_admin == 1;
}
// app/Models/User.php
//Model User
 public function events()
{
    return $this->belongsToMany(Event::class, 'event_user')
                ->withPivot('registered_at', 'checked_in_at', 'status', 'cancelled_at')
                ->withTimestamps();
}

//Model Event  
public function users()
{
    return $this->belongsToMany(User::class);
}


// In app/Models/User.php
protected static function booted()
{
    static::deleting(function ($user) {
        // Clean up pivot table when user is deleted
        DB::table('event_user')->where('user_id', $user->id)->delete();
    });
}

public function favoriteEvents()
{
    return $this->belongsToMany(Event::class, 'favorites');
}
}