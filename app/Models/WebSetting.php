<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WebSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'brand_name',
        'cta_label',
        'cta_url',
        'shop_url',
        'menu_items',
        'brotherhood_submenu_items',
        'cultos_submenu_items',
        'patrimonio_submenu_items',
        'obra_social_submenu_items',
        'hero_slides',
        'chapel_card_title',
        'chapel_blocks',
        'chapel_footer',
        'donation_card_title',
        'donation_blocks',
        'donation_footer',
    ];

    protected function casts(): array
    {
        return [
            'brand_name' => 'array',
            'cta_label' => 'array',
            'menu_items' => 'array',
            'brotherhood_submenu_items' => 'array',
            'cultos_submenu_items' => 'array',
            'patrimonio_submenu_items' => 'array',
            'obra_social_submenu_items' => 'array',
            'hero_slides' => 'array',
            'chapel_card_title' => 'array',
            'chapel_blocks' => 'array',
            'chapel_footer' => 'array',
            'donation_card_title' => 'array',
            'donation_blocks' => 'array',
            'donation_footer' => 'array',
        ];
    }
}
