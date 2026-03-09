# Dynamic CRUD System Documentation

A metadata-driven CRUD system for Laravel that automatically generates **Data Tables** (with Livewire sorting/filtering) and **Create/Edit Forms** based on configurations defined in your Models and Controllers.

---

## 1. Core Concept

The system follows a "Configuration-over-Code" philosophy:
1.  **The Model** defines the structure of the **Listing Table**.
2.  **The Controller** defines the **Validation Rules** and **Form Fields**.
3.  **The Blade Views** use loops to interpret this metadata and render UI components.

---

## 2. Model Configuration

To enable dynamic table rendering, your model must use the `HasDynamicTable` trait and implement the following methods:

### `getTableColumns()`
Defines which columns appear in the table.
- **Key:** Database column name.
- **label:** Display title for the table header.
- **sortable:** (bool) Enables Livewire-based sorting.
- **type:** 
    - `text`: Standard string.
    - `date`: Formats value to `d M, Y`.
    - `number`: Standard numeric display.
    - `toggleable`: Renders a Livewire switch for boolean fields.
    - `badge`: Renders a colored pill (requires a `$badges` mapping in the view).
    - `image`: Renders an `<img>` tag from the stored path (assumes `storage` disk).
    - `relation`: Renders a field from a related model. Requires `relation` (method name) and `field` (attribute to display).
    

### `getTableFilters()`
Defines the dropdown filters appearing above the table.
- **label:** Dropdown label.
- **options:** `[ 'value' => 'Label' ]` associative array.

### ``
Defines the badge interface for the dynamic data table
- **Example:** 

    public function getTableBadges(): array
    {
        return [
            'role' => [
                'superadmin' => 'bg-danger text-white',
                'support'    => 'bg-info text-dark',
            ],
        ];
    }

- **key:** role in the example is the key of the model for badge
- **value:** value of the key is the key value pair (associative array) with key as the actual value for the key and the value is the class for badge.
---

## 3. Controller Configuration

Controllers must extend `BaseCrudController`.

### `rules($id = null)`
Standard Laravel validation rules. The `$id` parameter is passed automatically to handle "unique" validation during updates.

### `fields($item = null)`
Defines the structure of the Create/Edit form.
- **type:** `text`, `number`, `select`, `textarea`, `editor`, `image`, `json`.
- **label:** Field label.
- **required:** (bool) Displays an asterisk and adds HTML5 validation.
- **column:** Bootstrap grid class (e.g., `col-md-6`, `col-12`). Defaults to `col-md-6`.
- **options:** Array required for `select` types.

---

## 4. Implementation Example (Testimonials)

### A. The Model (`App\Models\Testimonial.php`)
```php

class Testimonial extends Model
{
    use HasDynamicTable;

    protected $fillable = ['author_name', 'company', 'rating', 'is_active'];

    public function getTableColumns(): array
    {
        return [
            'author_name' => ['label' => 'Author', 'sortable' => true, 'type' => 'text'],
            'company'     => ['label' => 'Company', 'sortable' => true, 'type' => 'text'],
            'rating'      => ['label' => 'Rating', 'sortable' => true, 'type' => 'number'],
            'is_active'   => ['label' => 'Status', 'sortable' => false, 'type' => 'toggleable'],
        ];
    }

    public function getTableFilters(): array
    {
        return [
            'is_active' => [
                'label' => 'Status',
                'options' => ['1' => 'Active', '0' => 'Inactive']
            ],
        ];
    }
}