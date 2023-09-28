<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Facture extends Model
{
    use HasFactory;

    protected $fillable = [
        "client",
        "facturier",
        "facture",
        "reference"
    ];

    function client(): BelongsTo
    {
        return $this->belongsTo(User::class, "client");
    }

    function facturier(): BelongsTo
    {
        return $this->belongsTo(User::class, "facturier");
    }
}
