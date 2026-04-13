<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ObraSocialPage extends Model
{
    use HasFactory;

    protected $table = 'obra_social_pages';

    protected $fillable = [
        'key',
        'title',
        'content',
        'charity_contact',
    ];

    protected function casts(): array
    {
        return [
            'title' => 'array',
            'content' => 'array',
            'charity_contact' => 'array',
        ];
    }
}
