<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AssessmentAspect extends Model
{
    use HasFactory;

    protected $table = 'assessment_aspects';

    protected $fillable = [
        'category_id',
        'name',
        'code',
        'target_score',
        'order',
    ];

    protected $casts = [
        'target_score' => 'float',
    ];

    /**
     * @return BelongsTo<AssessmentCategory, $this>
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(AssessmentCategory::class, 'category_id');
    }

    /**
     * @return HasMany<AssessmentScore, $this>
     */
    public function scores(): HasMany
    {
        return $this->hasMany(AssessmentScore::class, 'assessment_aspect_id');
    }
}
