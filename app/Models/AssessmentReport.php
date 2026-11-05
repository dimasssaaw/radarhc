<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssessmentReport extends Model
{
    use HasFactory;

    protected $table = 'assessment_report';

    protected $guarded = ['id'];

    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class, 'id_karyawan');
    }

    public function aspectScores()
    {
        return $this->hasMany(AssessmentScore::class, 'assessment_report_id');
    }

    /**
     * Get the score for a specific assessment aspect.
     * Looks up dynamic assessment_scores first, then falls back to legacy column if code matches.
     */
    public function getAspectScore(AssessmentAspect|int $aspect): float
    {
        $aspectId = is_int($aspect) ? $aspect : $aspect->id;
        $aspectObj = is_int($aspect) ? AssessmentAspect::find($aspectId) : $aspect;

        if ($this->relationLoaded('aspectScores')) {
            $scoreObj = $this->aspectScores->firstWhere('assessment_aspect_id', $aspectId);
            if ($scoreObj !== null && $scoreObj->score !== null) {
                return (float) $scoreObj->score;
            }
        } else {
            $scoreVal = $this->aspectScores()->where('assessment_aspect_id', $aspectId)->value('score');
            if ($scoreVal !== null) {
                return (float) $scoreVal;
            }
        }

        if ($aspectObj && $aspectObj->code && isset($this->attributes[$aspectObj->code]) && $this->attributes[$aspectObj->code] !== null) {
            return (float) $this->attributes[$aspectObj->code];
        }

        return 0.0;
    }
}
