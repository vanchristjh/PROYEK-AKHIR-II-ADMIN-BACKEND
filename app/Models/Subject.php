<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Subject extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'code',
        'description',
    ];

    /**
     * Get the teachers who teach this subject
     */
    public function teachers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'subject_teacher');
    }

    /**
     * Get the classrooms where this subject is taught
     */
    public function classrooms(): BelongsToMany
    {
        return $this->belongsToMany(Classroom::class, 'classroom_subject');
    }

    /**
     * Get the assignments for this subject
     */
    public function assignments(): HasMany
    {
        return $this->hasMany(Assignment::class);
    }

    /**
     * Get the materials for this subject
     */
    public function materials(): HasMany
    {
        return $this->hasMany(Material::class);
    }
}
