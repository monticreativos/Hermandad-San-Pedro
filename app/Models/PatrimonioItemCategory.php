<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PatrimonioItemCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'section_key',
        'name',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'name' => 'array',
        ];
    }

    /**
     * @return HasMany<PatrimonioItem, $this>
     */
    public function items(): HasMany
    {
        return $this->hasMany(PatrimonioItem::class, 'patrimonio_item_category_id');
    }
}
