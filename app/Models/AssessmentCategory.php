<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AssessmentCategory extends Model
{
    use HasFactory;

    protected $table = 'assessment_categories';

    protected $fillable = [
        'name',
        'code',
        'description',
        'order',
    ];

    /**
     * @return HasMany<AssessmentAspect, $this>
     */
    public function aspects(): HasMany
    {
        return $this->hasMany(AssessmentAspect::class, 'category_id')->orderBy('order')->orderBy('id');
    }
}
