<?php

namespace App\Http\Controllers;

use App\Models\Faq;

class FaqController extends BaseCrudController
{
    protected $model = Faq::class;
    protected $routePrefix = 'master.faqs';

    protected function rules($id = null): array
    {
        return [
            'question'   => 'required|string|max:255',
            'answer'     => 'required|string',
            'published'  => 'required|boolean',
            'sort_order' => 'nullable|integer',
        ];
    }

    protected function fields($item = null): array
    {
        return [
            'question' => [
                'type'     => 'text',
                'label'    => 'Question',
                'required' => true,
                'column'   => 'col-md-12',
            ],
            'published' => [
                'type'     => 'select',
                'label'    => 'Status',
                'options'  => [
                    '1' => 'Published',
                    '0' => 'Unpublished',
                ],
                'required' => true,
                'column'   => 'col-md-6',
            ],
            'sort_order' => [
                'type'     => 'number',
                'label'    => 'Sort Order',
                'column'   => 'col-md-6',
            ],
            'answer' => [
                'type'     => 'textarea',
                'label'    => 'Answer',
                'required' => true,
                'column'   => 'col-md-12',
            ],
        ];
    }
}
