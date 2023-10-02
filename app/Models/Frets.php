<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Frets extends Model
{
    use HasFactory;

    protected $fillable = [
        'depart_date',
        'arrived_date',
        'chargement_date',
        'delivery_hour',
        'fret_type',
        'weight',
        'length',
        'transport_type',
        'transport_num',
        'price',
        'comment',
        "depart_map",
        "arrived_map",
    ];

    // protected $hidden = [
    //     'owner',
    // ];

    function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, "owner");
    }

    function status(): BelongsTo
    {
        return $this->belongsTo(FretStatus::class, "status");
    }

    function transport_type(): BelongsTo
    {
        return $this->belongsTo(Type::class, "transport_type");
    }

    function fret_type(): BelongsTo
    {
        return $this->belongsTo(FretType::class, "fret_type");
    }

    function transport(): BelongsTo
    {
        return $this->belongsTo(Transport::class, "transport_id")->with("owner");
    }

    function marchandises(): HasMany
    {
        return $this->hasMany(Marchandise::class, "fret")->with("type");
    }
}
