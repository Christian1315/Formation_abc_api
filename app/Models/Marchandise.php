<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Marchandise extends Model
{
    use HasFactory;

    protected $fillable = [
        "fret",
        "type",
        "weight",
        "length",
    ];

    function fret():BelongsTo {
        return $this->belongsTo(Frets::class,"fret");
    }

    function type():BelongsTo {
        return $this->belongsTo(MarchandiseType::class,"type");
    }
}
