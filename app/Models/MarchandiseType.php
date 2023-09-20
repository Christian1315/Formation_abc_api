<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MarchandiseType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'image'
    ];

    protected $table = "marchandises_type";

    function marchandises(): HasMany
    {
        return $this->hasMany(Marchandise::class, "type");
    }
}
