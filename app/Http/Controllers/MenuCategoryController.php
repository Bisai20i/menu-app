<?php
namespace App\Http\Controllers;

use App\Models\MenuCategory;

class MenuCategoryController extends BaseCrudController
{
    protected $model       = MenuCategory::class;
    protected $routePrefix = 'master.menu-categories';
    protected $viewPath    = 'base-crud';
    protected $formPath    = 'base-crud';

    protected function rules($id = null): array
    {
        return [
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'image'       => 'nullable|image|max:2048',
            'sort_order'  => 'nullable|integer',
            'is_active'   => 'nullable|boolean',
        ];
    }

    protected function messages(): array
    {
        return [
            'name.required' => 'Category name is required.',
            'name.max' => 'Category name must not exceed 255 characters.',
            'image.image' => 'Please upload a valid image file (JPEG, PNG, GIF, or WebP format).',
            'image.max' => 'The image size must be less than 2 MB. Please upload a smaller image.',
            'sort_order.integer' => 'Sort order must be a whole number.',
        ];
    }

    protected function fields($item = null): array
    {
        return [
            'name'        => [
                'type'        => 'text',
                'label'       => 'Name',
                'placeholder' => 'Category Name',
                'required'    => true,
            ],
            'image'       => [
                'type'  => 'image',
                'label' => 'Category Image',
            ],
            'sort_order'  => [
                'type'    => 'number',
                'label'   => 'Sort Order',
                'placeholder' => "Smaller numbers appear first",
            ],
            'is_active'   => [
                'type'    => 'select',
                'label'   => 'Active',
                'options' => [
                    1 => 'Yes',
                    0 => 'No',
                ],
            ],
            'description' => [
                'type'        => 'textarea',
                'label'       => 'Description',
                'placeholder' => 'Category Description',
                'column'     => 'col-12',
            ],
        ];
    }
}
