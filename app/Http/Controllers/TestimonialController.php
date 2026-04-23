<?php

namespace App\Http\Controllers;

use App\Models\Testimonial;

class TestimonialController extends BaseCrudController
{
    protected $model = Testimonial::class;
    protected $routePrefix = 'master.testimonials';

    protected function rules($id = null): array
    {
        return [
            'name'        => 'required|string|max:255',
            'designation' => 'nullable|string|max:255',
            'avatar'      => 'nullable|image|max:2048',
            'content'     => 'required|string',
            'published'   => 'required|boolean',
            'sort_order'  => 'nullable|integer',
        ];
    }

    protected function fields($item = null): array
    {
        return [
            'name' => [
                'type'     => 'text',
                'label'    => 'Name',
                'required' => true,
                'column'   => 'col-md-6',
            ],
            'designation' => [
                'type'     => 'text',
                'label'    => 'Designation',
                'column'   => 'col-md-6',
                'placeholder' => 'e.g. CEO, Restaurant Owner',
            ],
            'avatar' => [
                'type'     => 'image',
                'label'    => 'Avatar',
                'column'   => 'col-md-6',
            ],
            'published' => [
                'type'     => 'select',
                'label'    => 'Status',
                'options'  => [
                    '1' => 'Published',
                    '0' => 'Unpublished',
                ],
                'required' => true,
                'column'   => 'col-md-3',
            ],
            'sort_order' => [
                'type'     => 'number',
                'label'    => 'Sort Order',
                'column'   => 'col-md-3',
            ],
            'content' => [
                'type'     => 'textarea',
                'label'    => 'Testimonial',
                'required' => true,
                'column'   => 'col-md-12',
            ],
        ];
    }
}
