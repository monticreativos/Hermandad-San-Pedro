<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CultosPage extends Model
{
    use HasFactory;

    protected $table = 'cultos_pages';

    protected $fillable = [
        'key',
        'title',
        'content',
    ];

    protected function casts(): array
    {
        return [
            'title' => 'array',
            'content' => 'array',
        ];
    }
}
