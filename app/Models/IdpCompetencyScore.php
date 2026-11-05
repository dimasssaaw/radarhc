<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class IdpCompetencyScore extends Model
{
    use HasFactory;

    protected $table = 'idp_competency_scores';

    protected $fillable = [
        'id_idp',
        'id_karyawan',
        'idp_competency_id',
        'score',
    ];

    protected $casts = [
        'score' => 'float',
    ];

    /**
     * @return BelongsTo<Idp, $this>
     */
    public function idp(): BelongsTo
    {
        return $this->belongsTo(Idp::class, 'id_idp');
    }

    /**
     * @return BelongsTo<Karyawan, $this>
     */
    public function karyawan(): BelongsTo
    {
        return $this->belongsTo(Karyawan::class, 'id_karyawan');
    }

    /**
     * @return BelongsTo<IdpCompetency, $this>
     */
    public function competency(): BelongsTo
    {
        return $this->belongsTo(IdpCompetency::class, 'idp_competency_id');
    }
}
