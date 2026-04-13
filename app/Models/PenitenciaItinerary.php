<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenitenciaItinerary extends Model
{
    use HasFactory;

    protected $fillable = [
        'year',
        'title',
        'stops',
    ];

    protected function casts(): array
    {
        return [
            'year' => 'integer',
            'title' => 'array',
            'stops' => 'array',
        ];
    }
}
