<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable(['user_id', 'title', 'description', 'category', 'poster', 'quota', 'date', 'time', 'location', 'points_reward'])]
class Event extends Model
{
    public function user()
    {
        return $this->belongsTo(User::class); // Organizer
    }

    public function registrations()
    {
        return $this->hasMany(EventRegistration::class);
    }

    public function activeRegistrations()
    {
        return $this->hasMany(EventRegistration::class)->where('status', 'registered');
    }

    public function isUserRegistered(?User $user): bool
    {
        if (!$user) {
            return false;
        }
        return $this->registrations()->where('user_id', $user->id)->where('status', 'registered')->exists();
    }

    public function hasReachedQuota(): bool
    {
        return $this->activeRegistrations()->count() >= $this->quota;
    }
}
