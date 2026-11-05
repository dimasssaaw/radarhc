<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AssessmentScore extends Model
{
    use HasFactory;

    protected $table = 'assessment_scores';

    protected $fillable = [
        'assessment_report_id',
        'assessment_aspect_id',
        'score',
    ];

    protected $casts = [
        'score' => 'float',
    ];

    /**
     * @return BelongsTo<AssessmentReport, $this>
     */
    public function assessmentReport(): BelongsTo
    {
        return $this->belongsTo(AssessmentReport::class, 'assessment_report_id');
    }

    /**
     * @return BelongsTo<AssessmentAspect, $this>
     */
    public function aspect(): BelongsTo
    {
        return $this->belongsTo(AssessmentAspect::class, 'assessment_aspect_id');
    }
}
