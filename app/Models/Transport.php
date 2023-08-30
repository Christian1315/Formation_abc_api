<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transport extends Model
{
    use HasFactory;

    protected $fillable = [
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

    protected $hidden = [
        'type_id'
    ];

    #ONE TO MANY\INVERSE RELATIONSHIP(UN MOYENS DE TRANSPORT PEUT APPARTENIR A UN ET UN SEUL USER[celui qui a le role **is_transporter**])
    function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, "owner")->with("roles");
    }

    #ONE TO MANY\INVERSE RELATIONSHIP(UN MOYENS DE TRANSPORT PEUT APPARTENIR A UN ET UN SEUL TYPE DE MOYEN DE TRANSPORT)
    function type(): BelongsTo
    {
        return $this->belongsTo(Type::class, "type_id");
    }
}
