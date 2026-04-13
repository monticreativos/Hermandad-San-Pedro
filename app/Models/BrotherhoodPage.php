<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BrotherhoodPage extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'title',
        'content',
        'legal_documents',
        'government_board',
    ];

    protected function casts(): array
    {
        return [
            'title' => 'array',
            'content' => 'array',
            'legal_documents' => 'array',
            'government_board' => 'array',
        ];
    }
}
