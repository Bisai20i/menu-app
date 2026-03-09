<?php

namespace App\Http\Controllers;

use App\Models\MenuItem;
use App\Models\MenuCategory;

class MenuItemController extends BaseCrudController
{
    protected $model = MenuItem::class;
    protected $routePrefix = 'master.menu-items';
    protected $viewPath = 'base-crud';
    protected $formPath = 'base-crud';

    protected function rules($id = null): array
    {
        return [
            'menu_category_id' => 'required|exists:menu_categories,id',
            'name'             => 'required|string|max:255',
            'description'      => 'nullable|string',
            'price'            => 'required|numeric|min:0',
            'image'            => 'nullable|image|max:2048',
            'is_available'     => 'nullable|boolean',
            'is_featured'      => 'nullable|boolean',
            'dietary_info'     => 'nullable|array',
        ];
    }

    protected function relations(): array
    {
        return [
            'menu_category_id' => MenuCategory::pluck('name', 'id')->toArray(),
        ];
    }

    protected function fields($item = null): array
    {
        return [
            'menu_category_id' => [
                'type' => 'select',
                'label' => 'Category',
                'options' => $this->relations()['menu_category_id'],
                'required' => true,
            ],
            'name' => [
                'type' => 'text',
                'label' => 'Item Name',
                'placeholder' => 'e.g. Grilled Chicken',
                'required' => true,
            ],
            'price' => [
                'type' => 'number',
                'label' => 'Price',
                'placeholder' => '0.00',
                'step' => '0.01',
                'required' => true,
            ],
            'is_available' => [
                'type' => 'select',
                'label' => 'Available',
                'options' => [
                    1 => 'Yes',
                    0 => 'No',
                ],
                'default' => true,
            ],
            'is_featured' => [
                'type' => 'select',
                'label' => 'Featured',
                'options' => [
                    1 => 'Yes',
                    0 => 'No',
                ],
                'default' => false,
            ],
            
            'image' => [
                'type' => 'image',
                'label' => 'Item Image',
            ],
            'description' => [
                'type' => 'textarea',
                'label' => 'Description',
                'placeholder' => 'Item Description',
                    'column' => 'col-12',
            ],
            
        ];
    }
}
