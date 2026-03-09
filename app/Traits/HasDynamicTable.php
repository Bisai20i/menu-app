<?php

namespace App\Traits;

trait HasDynamicTable
{
    /**
     * Define columns: 'key' => ['label' => '', 'sortable' => true, 'type' => 'text', 'toggleable' => false]
     */
    abstract public function getTableColumns(): array;

    /**
     * Define filters: 'field' => ['label' => '', 'options' => ['val' => 'Label']]
     */
    public function getTableFilters(): array
    {
        return [];
    }

    /**
     * Define relations to eager load
     */
    public function getTableRelations(): array
    {
        return [];
    }

    /**
     * Define badge mappings: 'field' => ['value' => 'bootstrap-color']
     */
    public function getTableBadges(): array
    {
        return [];
    }
}