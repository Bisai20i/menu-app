<?php
namespace App\Livewire;

use Illuminate\Database\Eloquent\Builder;
use Livewire\Component;
use Livewire\WithPagination;

class DynamicDataTable extends Component
{
    use WithPagination;

    // Props
    public string $model;
    public string $routePrefix;

    // State
    public string $search        = '';
    public array $filters        = [];
    public string $sortField     = 'created_at';
    public string $sortDirection = 'desc';
    public int $perPage          = 10;

    protected $queryString = ['search', 'sortField', 'sortDirection'];

    public function placeholder()
    {
        return view('livewire.placeholders.dynamic-table-placeholder');
    }

    public function mount(string $model, string $routePrefix)
    {
        // Convert kebab-case or snake_case to PascalCase (StudlyCase)
        $modelName = \Illuminate\Support\Str::studly($model);
        $fullModelPath = "App\\Models\\" . $modelName;

        if (!class_exists($fullModelPath)) {
            // Log the error for easier debugging
            \Illuminate\Support\Facades\Log::error("DynamicDataTable: Model [{$fullModelPath}] not found. Provided model string: [{$model}]");
            throw new \Exception("Model [{$fullModelPath}] not found. Check your model property in the Blade component.");
        }

        $this->model       = $fullModelPath;
        $this->routePrefix = $routePrefix;
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField     = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function toggleBoolean($id, $field)
    {
        $item = $this->model::findOrFail($id);
        $item->update([$field => ! $item->$field]);
        session()->flash('success', 'Status updated successfully.');
    }

    public function deleteSelected($ids) // Accept ids as parameter
    {
        if (empty($ids)) {
            return;
        }

        $this->model::whereIn('id', $ids)->delete();

        $this->dispatch('items-deleted');
        session()->flash('success', 'Selected records removed.');
    }

    public function deleteItem($id)
    {
        $this->model::findOrFail($id)->delete();
        $this->dispatch('item-deleted');
        session()->flash('success', 'Record deleted successfully.');
    }

    public function clearFilters()
    {
        $this->reset(['search', 'filters', 'sortField', 'sortDirection']);
    }

    public function render()
    {
        $instance      = new $this->model;
        $columns       = $instance->getTableColumns();
        $filterConfigs = $instance->getTableFilters();
        $badges        = $instance->getTableBadges();

        $query = $this->model::query()
            ->with($instance->getTableRelations())
            ->when($this->search, function (Builder $q) use ($columns) {
                $q->where(function ($sub) use ($columns) {
                    foreach ($columns as $key => $col) {
                        if ($col['type'] === 'relation') {
                            $sub->orWhereHas($col['relation'], function ($query) use ($col) {
                                $query->where($col['field'], 'like', '%' . $this->search . '%');
                            });
                        } elseif ($col['type'] === 'text' || $col['type'] === 'email') {
                            $sub->orWhere($key, 'like', '%' . $this->search . '%');
                        }
                    }
                });
            });

        // Apply Dynamic Filters
        foreach ($this->filters as $field => $value) {
            if ($value !== '') {
                $query->where($field, $value);
            }
        }

        $items = $query->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.dynamic-data-table', [
            'items'         => $items,
            'columns'       => $columns,
            'filterConfigs' => $filterConfigs,
            'badges'        => $badges,
        ]);
    }
}
