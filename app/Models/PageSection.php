<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PageSection extends Model
{
     protected $fillable = [
        'page_id',
        'section_type',
        'title',
        'subtitle',
        'content',
        'image_path',
        'button_text',
        'button_url',
        'sort_order',
        'is_visible',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'content' => 'array',
            'is_visible' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    public function page(): BelongsTo
    {
        return $this->belongsTo(Page::class);
    }
}
