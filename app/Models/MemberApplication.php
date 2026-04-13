<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MemberApplication extends Model
{
    protected $fillable = [
        'full_name',
        'email',
        'phone',
        'birth_date',
        'address',
        'message',
        'locale',
    ];

    protected function casts(): array
    {
        return [
            'birth_date' => 'date',
        ];
    }
}
