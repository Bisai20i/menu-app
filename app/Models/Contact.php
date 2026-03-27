<?php

namespace App\Models;

use App\Traits\HasDynamicTable;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use HasDynamicTable;
    
    protected $fillable = [
        'name',
        'email',
        'message',
        'read_at',
    ];

    protected $casts = [
        'read_at' => 'datetime',
    ];

    public function getTableColumns(): array
    {
        return [
            'name' => [
                'label'    => 'Name',
                'sortable' => true,
                'type'     => 'text',
            ],
            'email' => [
                'label'    => 'Email',
                'sortable' => true,
                'type'     => 'text',
            ],
            'created_at' => [
                'label'    => 'Received At',
                'sortable' => true,
                'type'     => 'date',
            ],
        ];
    }
}
