<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Transport extends Model
{
    use HasFactory;

    protected $fillable = [
        "name",
        'fabric_year',
        'circulation_year',
        'tech_visit_expire',
        'assurance_expire',

        'gris_card',
        'assurance_card',

        "img1",
        "img2",
        "img3",

        'type_id',
    ];

    // protected $hidden = [
    //     'type_id'
    // ];

    function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, "owner")->with("roles");
    }

    function type(): BelongsTo
    {
        return $this->belongsTo(Type::class, "type_id");
    }

    function status(): BelongsTo
    {
        return $this->belongsTo(TransportStatus::class, "status");
    }

    function frets(): HasMany
    {
        return $this->hasMany(Frets::class, "transport_id")->with(["owner","status"]);
    }
}
