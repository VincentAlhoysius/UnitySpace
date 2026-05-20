<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable(['user_id', 'title', 'content', 'category', 'likes_count', 'reports_count'])]
class Forum extends Model
{
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function comments()
    {
        return $this->hasMany(ForumComment::class);
    }

    public function rootComments()
    {
        return $this->hasMany(ForumComment::class)->whereNull('parent_id');
    }

    public function likes()
    {
        return $this->hasMany(ForumLike::class);
    }

    public function isLikedByUser(?User $user): bool
    {
        if (!$user) {
            return false;
        }
        return $this->likes()->where('user_id', $user->id)->exists();
    }
}
