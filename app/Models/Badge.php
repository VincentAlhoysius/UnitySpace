<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable(['name', 'description', 'image', 'point_requirement', 'badge_type'])]
class Badge extends Model
{
    public function users()
    {
        return $this->belongsToMany(User::class, 'user_badges')->withPivot('earned_at')->withTimestamps();
    }
}
