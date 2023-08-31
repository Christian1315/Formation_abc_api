<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Frets extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'nature',
        'vol_or_quant',
        'charg_date',
        'charg_location',
        'charg_destination',
        'axles_num',
        'fret_img',
    ];

    protected $hidden = [
        'user_id',
    ];

    function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, "owner");
    }

    function status(): BelongsTo
    {
        return $this->belongsTo(FretStatus::class, "status");
    }
}
