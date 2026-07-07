<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     * Included 'ward' and 'school_id' to support your administrative infrastructure roles.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'role_status',
        'ward',       // Added so WEO ward records can be saved safely
        'school_id',  // Added to link institutional staff accounts to their schools
        'teaching_subject_id',
        'teaching_class_id',
    ];

    /**
     * The attributes that should be hidden for serialization arrays.
     *
     * @var array<int, string>
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

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    public function teachingSubject(): BelongsTo
    {
        return $this->belongsTo(Subject::class, 'teaching_subject_id');
    }

    public function teachingClass(): BelongsTo
    {
        return $this->belongsTo(SchoolClass::class, 'teaching_class_id');
    }

    public function teachingClasses(): BelongsToMany
    {
        return $this->belongsToMany(SchoolClass::class)->withTimestamps();
    }
}
