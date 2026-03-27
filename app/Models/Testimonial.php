<?php

namespace App\Models;

use App\Traits\HasDynamicTable;
use Illuminate\Database\Eloquent\Model;

class Testimonial extends Model
{
    use HasDynamicTable;

    protected $fillable = [
        'name',
        'designation',
        'avatar',
        'content',
        'published',
        'sort_order',
    ];

    protected $casts = [
        'published' => 'boolean',
    ];

    public function getAvatarUrlAttribute(): string{
        return $this->avatar ? asset('storage/'.$this->avatar) : asset('frontend/images/default.png');
    }

    protected $appends = ['avatar_url'];
    public function getTableColumns(): array
    {
        return [
            'avatar' => [
                'label' => 'Avatar',
                'type'  => 'image',
            ],
            'name' => [
                'label'    => 'Name',
                'sortable' => true,
                'type'     => 'text',
            ],
            'designation' => [
                'label'    => 'Designation',
                'sortable' => true,
                'type'     => 'text',
            ],
            'published' => [
                'label'    => 'Published',
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
}
