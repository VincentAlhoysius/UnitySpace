<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable(['reporter_id', 'report_type', 'description', 'evidence_path', 'status'])]
class Report extends Model
{
    public function reporter()
    {
        return $this->belongsTo(User::class, 'reporter_id');
    }
}
