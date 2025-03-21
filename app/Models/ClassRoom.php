<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassRoom extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'class_rooms';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'level',
        'type',
        'capacity',
        'room',
        'teacher_id',
        'academic_year',
        'description',
    ];

    /**
     * Get the students for the class.
     */
    public function students()
    {
        return $this->hasMany(User::class, 'class_id');
    }

    /**
     * Get the teacher for the class.
     */
    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }
}
