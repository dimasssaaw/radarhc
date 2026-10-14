<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IdpPenilain extends Model
{
    use HasFactory;

    protected $table = 'idp_penilain';

    protected $guarded = [];

    public function idp()
    {
        return $this->belongsTo(Idp::class, 'id_idp');
    }
}
