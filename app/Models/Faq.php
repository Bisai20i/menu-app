<?php

namespace App\Models;

use App\Traits\HasDynamicTable;
use Illuminate\Database\Eloquent\Model;

class Faq extends Model
{
    use HasDynamicTable;

    protected $fillable = [
        'question',
        'answer',
        'published',
        'sort_order',
    ];

    protected $casts = [
        'published' => 'boolean',
    ];

    public function getTableColumns(): array
    {
        return [
            'question' => [
                'label'    => 'Question',
                'sortable' => true,
                'type'     => 'text',
            ],
            'published' => [
                'label'    => 'Published',
                'sortable' => true,
                'type'     => 'toggleable',
            ],
            'sort_order' => [
                'label'    => 'Sort Order',
                'sortable' => true,
                'type'     => 'text',
            ],
            'created_at' => [
                'label'    => 'Created At',
                'sortable' => true,
                'type'     => 'date',
            ],
        ];
    }
}
