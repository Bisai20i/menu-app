<?php
namespace App\Models;

use App\Traits\HasDynamicTable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SubscriptionPlan extends Model
{
    use HasDynamicTable;

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (!$model->admin_id) {
                $model->admin_id = auth('admin')->id();
            }
        });
    }
    protected $fillable = [
        'admin_id',
        'name',
        'duration_value',
        'duration_unit',
        'price',
        'currency',
        'features',
        'published',
        'sort_order',
    ];

    protected $casts = [
        'features'  => 'array',
        'published' => 'boolean',
        'price'     => 'decimal:2',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function admin(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'admin_id');
    }

    /*
    |--------------------------------------------------------------------------
    | Dynamic Table Columns
    |--------------------------------------------------------------------------
    */

    public function getTableColumns(): array
    {
        return [
            'name'           => [
                'label'    => 'Plan Name',
                'sortable' => true,
                'type'     => 'text',
            ],

            'duration_value' => [
                'label'    => 'Duration',
                'sortable' => true,
                'type'     => 'text',
            ],

            'price'          => [
                'label'    => 'Price',
                'sortable' => true,
                'type'     => 'currency',
            ],

            'published'      => [
                'label'    => 'Published',
                'sortable' => false,
                'type'     => 'toggleable',
            ],

            'created_at'     => [
                'label'    => 'Created',
                'sortable' => true,
                'type'     => 'date',
            ],
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Table Filters
    |--------------------------------------------------------------------------
    */

    public function getTableFilters(): array
    {
        return [
            'published'     => [
                'label'   => 'Status',
                'options' => [
                    '1' => 'Published',
                    '0' => 'Unpublished',
                ],
            ],

            'duration_unit' => [
                'label'   => 'Duration Unit',
                'options' => [
                    'month' => 'Month',
                    'year'  => 'Year',
                    'day'   => 'Day',
                ],
            ],
        ];
    }
}
