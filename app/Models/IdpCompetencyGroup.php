<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class IdpCompetencyGroup extends Model
{
    use HasFactory;

    protected $table = 'idp_competency_groups';

    protected $fillable = [
        'title',
        'target_position',
        'description',
        'order',
    ];

    /**
     * @return HasMany<IdpCompetency, $this>
     */
    public function competencies(): HasMany
    {
        return $this->hasMany(IdpCompetency::class, 'idp_competency_group_id')->orderBy('order')->orderBy('id');
    }
}
