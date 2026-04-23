<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;

class ArticleController extends BaseCrudController
{
    protected $model = Article::class;
    protected $routePrefix = 'master.articles';

    /**
     * Define validation rules.
     */
    protected function rules($id = null): array
    {
        return [
            'title'            => 'required|string|max:255',
            'slug'             => 'nullable|string|max:255|unique:articles,slug,' . $id,
            'thumbnail'        => 'nullable|image|max:2048',
            'content'          => 'nullable|string',
            'meta_title'       => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'meta_keywords'    => 'nullable|string|max:255',
            'is_active'        => 'nullable|boolean',
        ];
    }

    /**
     * Define fields for the form.
     */
    protected function fields($item = null): array
    {
        return [
            'title' => [
                'label'    => 'Article Title',
                'type'     => 'text',
                'required' => true,
                'column'   => 'col-md-12',
            ],
            'thumbnail' => [
                'label'    => 'Thumbnail Image',
                'type'     => 'image',
                'required' => false,
            ],
            'is_active' => [
                'label'   => 'Status',
                'type'    => 'select',
                'options' => [
                    '1' => 'Published',
                    '0' => 'Draft',
                ],
            ],
            'content' => [
                'label'  => 'Article Content',
                'type'   => 'editor',
                'column' => 'col-md-12',
            ],
            // SEO Section
            'meta_title' => [
                'label'  => 'SEO Meta Title',
                'type'   => 'text',
                'column' => 'col-md-12',
                'help'   => 'Recommended length: 50-60 characters.',
            ],
            'meta_description' => [
                'label'  => 'SEO Meta Description',
                'type'   => 'textarea',
                'column' => 'col-md-12',
                'help'   => 'Recommended length: 150-160 characters.',
            ],
            'meta_keywords' => [
                'label'  => 'SEO Meta Keywords',
                'type'   => 'text',
                'column' => 'col-md-12',
                'help'   => 'Comma separated keywords.',
            ],
        ];
    }
}
