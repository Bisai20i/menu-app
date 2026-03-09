<?php

namespace App\Http\Controllers;

use App\Models\RestaurantTable;

class RestaurantTableController extends BaseCrudController
{
    protected $model       = RestaurantTable::class;
    protected $routePrefix = 'master.restaurant-tables';

    protected function rules($id = null): array
    {
        return [
            'table_number' => 'required|string|max:255',
            'capacity'     => 'required|integer|min:1',
            'section'      => 'nullable|string|max:255',
            'is_active'    => 'nullable|boolean',
        ];
    }

    protected function fields($item = null): array
    {
        return [
            'table_number' => [
                'type'        => 'text',
                'label'       => 'Table Number',
                'placeholder' => 'e.g., T-01, VIP-1',
                'required'    => true,
            ],
            'capacity'     => [
                'type'        => 'number',
                'label'       => 'Capacity (Seats)',
                'placeholder' => 'Enter number of seats',
                'required'    => true,
            ],
            'section'      => [
                'type'        => 'text',
                'label'       => 'Section',
                'placeholder' => 'e.g., Main Hall, Terrace',
            ],
            'is_active'    => [
                'type'    => 'select',
                'label'   => 'Is Active?',
                'options' => [
                    1 => 'Yes',
                    0 => 'No',
                ],
            ],
        ];
    }
}
