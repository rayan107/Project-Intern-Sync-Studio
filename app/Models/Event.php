<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Event extends Model
{
    protected $fillable = [
        'admin_id',
        'title',
        'description',
        'event_date',
        'location',
        'image',
        'price',
        'start_time',
        'end_time',
    ];

    /**
     * Get the admin that owns the event.
     */
    public function admin(): BelongsTo
    {
        return $this->belongsTo(Admin::class);
    }

    public function favoritedByUsers()
{
    return $this->belongsToMany(User::class, 'favorites');
}

    /**
     * Get the images for the event.
     */
    public function images(): HasMany
    {
        return $this->hasMany(EventImage::class)->orderBy('sort_order');
    }

    /**
     * Get the users registered for the event.
     */
   public function users()
{
    return $this->belongsToMany(User::class, 'event_user')
                ->withPivot('registered_at', 'checked_in_at', 'status', 'cancelled_at')
                ->withTimestamps();
}
    /**
     * Get the reviews for the event.
     */
    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    /**
     * Get the messages for the event.
     */
    public function messages(): HasMany
    {
        return $this->hasMany(Message::class);
    }

    /**
     * Get average rating of the event.
     */
    public function getAverageRatingAttribute(): float
    {
        return (float) ($this->reviews()->avg('rating') ?? 0);
    }

    /**
     * Get total reviews count.
     */
    public function getReviewsCountAttribute(): int
    {
        return (int) $this->reviews()->count();
    }

    /**
     * Boot the model.
     */
    protected static function booted(): void
    {
        static::deleting(function ($event) {
            // Clean up pivot table when event is deleted
            $event->users()->detach();
        });
    }
}