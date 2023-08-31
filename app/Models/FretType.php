<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FretType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'image'
    ];

    function frets(): HasMany
    {
        return $this->hasMany(Frets::class, "fret_types");
    }
}
