<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class GradeCategory extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'weight',
        'description',
    ];

    /**
     * Get the grade items that belong to this category.
     */
    public function gradeItems(): HasMany
    {
        return $this->hasMany(GradeItem::class, 'category_id');
    }
}
