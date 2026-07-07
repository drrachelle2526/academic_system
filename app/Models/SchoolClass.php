<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SchoolClass extends Model
{
    protected $fillable = [
        'school_id',
        'name',
        'stream',
        'class_teacher_id',
    ];

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    public function learners(): HasMany
    {
        return $this->hasMany(Learner::class);
    }

    public function teachers(): BelongsToMany
    {
        return $this->belongsToMany(User::class)->withTimestamps();
    }
}
