<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PatrimonioPage extends Model
{
    use HasFactory;

    protected $table = 'patrimonio_pages';

    protected $fillable = [
        'key',
        'title',
        'content',
        'gallery',
    ];

    protected function casts(): array
    {
        return [
            'title' => 'array',
            'content' => 'array',
            'gallery' => 'array',
        ];
    }
}
