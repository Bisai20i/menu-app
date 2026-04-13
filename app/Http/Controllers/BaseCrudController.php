<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use ReflectionClass;

abstract class BaseCrudController extends Controller
{
    protected $model;
    protected $routePrefix;
    protected $viewPath = 'base-crud'; // e.g., 'admin.pages.crud'

    protected $formPath       = 'base-crud'; // used for dynamic form ( create and edit )
    protected $imageDirectory = 'uploads';
    protected $jsonFields     = [];
    protected $arrayFields    = [];

    // Child must define these
    abstract protected function rules($id = null): array;
    abstract protected function fields($item = null): array;

    /**
     * Define relations needed for Create/Edit forms (e.g., Categories, Roles)
     */
    protected function relations(): array
    {
        return [];
    }

    public function index()
    {
        return view($this->viewPath . '.index', [
            'routePrefix' => $this->routePrefix,
            'model'       => (new ReflectionClass($this->model))->getShortName(),
        ]);
    }

    public function create()
    {
        return view($this->formPath . '.form', [
            'item'        => new $this->model,
            'fields'      => $this->fields(),
            'relations'   => $this->relations(),
            'route'       => route($this->routePrefix . '.store'),
            'method'      => 'POST',
            'title'       => 'Create New',
            'routePrefix' => $this->routePrefix,
        ]);
    }

    public function store(Request $request)
    {
        // return $request->all();

        $validated = $request->validate($this->rules());

        $data = $this->handleFileUploads($request, $validated);
        $data = $this->handleJsonFields($request, $data);
        $data = $this->handleArrayFields($request, $data);
        $this->model::create($data);
  
        return redirect()->route($this->routePrefix . '.index')
            ->with('success', 'Record created successfully.');
    }

    public function edit($id)
    {
        $item = $this->model::findOrFail($id);
        $this->authorizeOwnership($item);

        return view($this->formPath . '.form', [
            'item'        => $item,
            'fields'      => $this->fields($item),
            'relations'   => $this->relations(),
            'route'       => route($this->routePrefix . '.update', $item->id),
            'method'      => 'PUT',
            'title'       => 'Edit Record',
            'routePrefix' => $this->routePrefix,
        ]);
    }

    public function update(Request $request, $id)
    {
        $item      = $this->model::findOrFail($id);
        $this->authorizeOwnership($item);
        $validated = $request->validate($this->rules($id));

        $data = $this->handleFileUploads($request, $validated, $item);
        $data = $this->handleJsonFields($request, $data);
        $data = $this->handleArrayFields($request, $data);
        $item->update($data);

        return redirect()->route($this->routePrefix . '.index')
            ->with('success', 'Record updated successfully.');
    }

    public function destroy($id)
    {
        $item = $this->model::findOrFail($id);
        $this->authorizeOwnership($item);

        // Clean up images defined in fields
        foreach ($this->fields() as $name => $meta) {
            if ($meta['type'] === 'image' && $item->$name) {
                Storage::disk('public')->delete($item->$name);
            }
        }

        $item->delete();

        return redirect()->route($this->routePrefix . '.index')
            ->with('success', 'Record deleted successfully.');
    }

    /**
     * Abort with 403 if the record does not belong to the current admin's restaurant.
     * This is defense-in-depth: the global scope already prevents fetching foreign records,
     * but this explicit check blocks any path that bypasses Eloquent scopes.
     */
    protected function authorizeOwnership(mixed $item): void
    {
        if (
            Auth::guard('admin')->check()
            && property_exists($item, 'restaurant_id')
            && Auth::guard('admin')->user()->restaurant_id !== $item->restaurant_id
        ) {
            abort(403, 'You do not have permission to access this resource.');
        }
    }

    /**
     * Automatically handles image uploads based on field metadata
     */
    protected function handleFileUploads(Request $request, array $data, $item = null): array
    {
        foreach ($this->fields() as $name => $meta) {
            if ($meta['type'] === 'image' && $request->hasFile($name)) {
                // Delete old image if updating
                if ($item && $item->$name) {
                    Storage::disk('public')->delete($item->$name);
                }
                $data[$name] = $request->file($name)->store($this->imageDirectory, 'public');
            }
        }
        return $data;
    }

    /**
     * Automatically handle json fields with key value
     * @param Request $request
     * @param array $data
     * @return array
     */
    protected function handleJsonFields(Request $request, array $data): array
    {
        foreach ($this->jsonFields ?? [] as $field) {
            if (! $request->has($field)) {
                continue;
            }

            $data[$field] = collect($request->input($field))
                ->filter(fn($row) =>
                    is_array($row)
                    && ! empty($row['key'])
                    && array_key_exists('value', $row)
                )
                ->mapWithKeys(fn($row) => [
                    $row['key'] => $row['value'],
                ])
                ->toArray();
        }

        return $data;
    }

    /**
     * Automatically handle simple array fields (list of values)
     * @param Request $request
     * @param array $data
     * @return array
     */
    protected function handleArrayFields(Request $request, array $data): array
    {
        foreach ($this->arrayFields ?? [] as $field) {
            if (! $request->has($field)) {
                continue;
            }

            $data[$field] = collect($request->input($field))
                ->filter(fn($val) => ! is_null($val) && (string)$val !== '')
                ->values()
                ->toArray();
        }

        return $data;
    }

}
