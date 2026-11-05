<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class IdpCompetency extends Model
{
    use HasFactory;

    protected $table = 'idp_competencies';

    protected $fillable = [
        'idp_competency_group_id',
        'name',
        'code',
        'description',
        'target_score',
        'order',
    ];

    protected $casts = [
        'target_score' => 'float',
    ];

    /**
     * @return BelongsTo<IdpCompetencyGroup, $this>
     */
    public function group(): BelongsTo
    {
        return $this->belongsTo(IdpCompetencyGroup::class, 'idp_competency_group_id');
    }

    /**
     * @return HasMany<IdpCompetencyScore, $this>
     */
    public function scores(): HasMany
    {
        return $this->hasMany(IdpCompetencyScore::class, 'idp_competency_id');
    }
}
