<?php

namespace App\Models;

use App\Traits\HasDynamicTable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Article extends Model
{
    use HasFactory, HasDynamicTable;

    protected $fillable = [
        'title',
        'slug',
        'content',
        'thumbnail',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($article) {
            if (empty($article->slug)) {
                $article->slug = Str::slug($article->title);
            }
        });

        static::updating(function ($article) {
            if ($article->isDirty('title') && empty($article->slug)) {
                $article->slug = Str::slug($article->title);
            }
        });
    }

    /**
     * Define columns for the dynamic table.
     */
    public function getTableColumns(): array
    {
        return [
            'thumbnail' => [
                'label'    => 'Thumbnail',
                'sortable' => false,
                'type'     => 'image',
            ],
            'title' => [
                'label'    => 'Title',
                'sortable' => true,
                'type'     => 'text',
            ],
            'is_active' => [
                'label'    => 'Status',
                'sortable' => true,
                'type'     => 'toggleable',
            ],
            'created_at' => [
                'label'    => 'Created At',
                'sortable' => true,
                'type'     => 'date',
            ],
        ];
    }

    /**
     * Define badge mappings.
     */
    public function getTableBadges(): array
    {
        return [
            'is_active' => [
                '1' => 'success',
                '0' => 'danger',
            ],
        ];
    }
}
