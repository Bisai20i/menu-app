<?php

namespace App\Models;

use App\Traits\BelongsToRestaurant;
use App\Traits\HasDynamicTable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory, BelongsToRestaurant, HasDynamicTable;

    protected $fillable = [
        'rating',
        'comment',
        'source',
        'ip_address',
        'user_agent',
        'restaurant_id',
        'redirected_to_google',
        'is_public',
    ];

    /**
     * Columns to show in the DynamicDataTable
     */
    public function getTableColumns(): array
    {
        return [
            'rating' => ['label' => 'Rating', 'type' => 'text', 'sortable' => true],
            'comment' => ['label' => 'Comment', 'type' => 'text'],
            'source' => ['label' => 'Source', 'type' => 'badge', 'sortable' => true],
            'redirected_to_google' => ['label' => 'Google Redirect', 'type' => 'badge', 'sortable' => true],
            'is_public' => ['label' => 'Public Status', 'type' => 'toggleable', 'sortable' => true],
            'created_at' => ['label' => 'Date', 'type' => 'date', 'sortable' => true],
        ];
    }

    /**
     * Filters for the DynamicDataTable
     */
    public function getTableFilters(): array
    {
        return [
            'rating' => [
                'label' => 'Rating',
                'options' => [
                    '1' => '1 Star',
                    '2' => '2 Stars',
                    '3' => '3 Stars',
                    '4' => '4 Stars',
                    '5' => '5 Stars',
                ]
            ],
            'source' => [
                'label' => 'Source',
                'options' => [
                    'internal' => 'Internal',
                    'google_redirect' => 'Google Redirect',
                ]
            ],
            'redirected_to_google' => [
                'label' => 'Converted to Google',
                'options' => [
                    '0' => 'No',
                    '1' => 'Yes',
                ]
            ],
        ];
    }

    /**
     * Badge styles for the DynamicDataTable
     */
    public function getTableBadges(): array
    {
        return [
            'source' => [
                'internal' => 'bg-info',
                'google_redirect' => 'bg-warning',
            ],
            'redirected_to_google' => [
                0 => 'bg-secondary',
                1 => 'bg-success',
            ]
        ];
    }

    /**
     * Relations to eager load
     */
    public function getTableRelations(): array
    {
        return [];
    }
}
