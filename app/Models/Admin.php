<?php
namespace App\Models;

use App\Traits\HasDynamicTable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticable;
use Illuminate\Notifications\Notifiable;

class Admin extends Authenticable
{
    use Notifiable;
    use HasDynamicTable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'image',
        'is_active',
        'restaurant_id',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * check if the admin is super admin
     */
    public function getIsSuperAdminAttribute()
    {
        return $this->role === 'superadmin';
    }

    /**
     * Get the full URL for the PAN document.
     */
    public function getImageUrlAttribute()
    {
        return $this->image ? asset('storage/' . $this->image) : asset('logo.png');
    }

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * get the restaurant associated with the admin
     */
    public function restaurant():BelongsTo
    {
        return $this->belongsTo(Restaurant::class);
    }


    /**
     * Summary of getTableColumns
     * @return array{created_at: array{label: string, sortable: bool, type: string, email: array{label: string, sortable: bool, type: string}, is_active: array{label: string, sortable: bool, type: string}, name: array{label: string, sortable: bool, type: string}, role: array{label: string, sortable: bool, type: string}}}
     */
    public function getTableColumns(): array
    {
        return [
            'name'       => ['label' => 'Name', 'sortable' => true, 'type' => 'text'],
            'email'      => ['label' => 'Email', 'sortable' => true, 'type' => 'text'],
            'role'       => ['label' => 'Role', 'sortable' => true, 'type' => 'badge'],
            'is_active'  => ['label' => 'Status', 'sortable' => true, 'type' => 'toggleable'],
            'created_at' => ['label' => 'Joined', 'sortable' => true, 'type' => 'date'],
        ];
    }
    /**
     * Summary of getTableFilters
     * @return array{role: array}
     */
    public function getTableFilters(): array
    {
        return [
            'role' => [
                'label'   => 'Roles',
                'options' => ['superadmin' => 'Super Admin', 'admin' => 'Admin'],
            ],
        ];
    }

    /**
     * Summary of getTableBadges
     * @return array{role: array{superadmin: string, admin: string}}
     */
    public function getTableBadges(): array
    {
        return [
            'role' => [
                'superadmin' => 'bg-danger text-white',
                'admin'      => 'bg-primary text-white',
                'support'    => 'bg-info text-dark',
            ],
        ];
    }
}
