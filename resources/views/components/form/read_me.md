# Laravel 12 Bootstrap 5 Form Components

Complete set of form components with automatic validation error handling for Laravel 12 and Bootstrap 5.

## Installation

1. **Copy components to your project:**
   Place all component files in `resources/views/components/form/` directory:
   ```
   resources/views/components/form/
   ├── label.blade.php
   ├── input.blade.php
   ├── textarea.blade.php
   ├── select.blade.php
   └── text-editor.blade.php
   ```

2. **Ensure your layout has stack directives:**
   In `resources/views/layouts/main.blade.php`:
   ```blade
   <head>
       <!-- Your head content -->
       @stack('styles')
   </head>
   <body>
       <!-- Your body content -->
       @stack('scripts')
   </body>
   ```

## Components

### 1. Label Component
Simple label component with optional required indicator.

**Usage:**
```blade
<x-form.label for="username" required>
    Username
</x-form.label>
```

**Props:**
- `for` - Input ID to associate with
- `required` - Show required asterisk (boolean)

---

### 2. Input Component
Input field with automatic validation error handling.

**Basic Usage:**
```blade
<x-form.input 
    name="title" 
    label="Title"
    required
/>
```

**Advanced Usage:**
```blade
<x-form.input 
    type="email"
    name="email" 
    label="Email Address"
    placeholder="Enter your email"
    value="{{ $user->email ?? '' }}"
    help="We'll never share your email with anyone else."
    required
/>
```

**Props:**
- `type` - Input type (default: 'text')
- `name` - Input name (required)
- `label` - Label text
- `value` - Default value
- `placeholder` - Placeholder text
- `required` - Required field (boolean)
- `readonly` - Read-only field (boolean)
- `disabled` - Disabled field (boolean)
- `help` - Help text below input

**Supported Types:**
text, email, password, number, tel, url, date, time, datetime-local, etc.

---

### 3. Textarea Component
Textarea field with automatic validation error handling.

**Basic Usage:**
```blade
<x-form.textarea 
    name="description" 
    label="Description"
/>
```

**Advanced Usage:**
```blade
<x-form.textarea 
    name="bio" 
    label="Biography"
    rows="6"
    placeholder="Tell us about yourself"
    value="{{ $user->bio ?? '' }}"
    help="Maximum 500 characters"
    required
/>
```

**Props:**
- `name` - Textarea name (required)
- `label` - Label text
- `value` - Default value
- `placeholder` - Placeholder text
- `rows` - Number of rows (default: 4)
- `required` - Required field (boolean)
- `readonly` - Read-only field (boolean)
- `disabled` - Disabled field (boolean)
- `help` - Help text below textarea

---

### 4. Select Component
Select dropdown with automatic validation error handling. Supports arrays and collections.

**Simple Array Usage:**
```blade
<x-form.select 
    name="status" 
    label="Status"
    :options="['active' => 'Active', 'inactive' => 'Inactive']"
    selected="active"
/>
```

**Collection Usage:**
```blade
<x-form.select 
    name="category_id" 
    label="Category"
    :options="$categories"
    valueKey="id"
    labelKey="name"
    placeholder="Select a category"
    :selected="$post->category_id ?? ''"
    required
/>
```

**Advanced Usage with Help Text:**
```blade
<x-form.select 
    name="role" 
    label="User Role"
    :options="[
        'admin' => 'Administrator',
        'editor' => 'Editor',
        'viewer' => 'Viewer'
    ]"
    help="Select the appropriate role for this user"
    required
/>
```

**Props:**
- `name` - Select name (required)
- `label` - Label text
- `options` - Array or collection of options
- `selected` - Selected value
- `placeholder` - Default placeholder option
- `required` - Required field (boolean)
- `disabled` - Disabled field (boolean)
- `help` - Help text below select
- `valueKey` - Key for value (default: 'id')
- `labelKey` - Key for label (default: 'name')

---

### 5. Text Editor Component
Rich text editor using Quill.js with automatic validation error handling.

**Basic Usage:**
```blade
<x-form.text-editor 
    name="content" 
    label="Content"
/>
```

**Advanced Usage:**
```blade
<x-form.text-editor 
    name="article_body" 
    label="Article Content"
    value="{{ $article->body ?? '' }}"
    height="400px"
    placeholder="Start writing your article..."
    help="Use the toolbar to format your content"
    required
/>
```

**Props:**
- `name` - Input name (required)
- `label` - Label text
- `value` - Default HTML content
- `placeholder` - Placeholder text
- `required` - Required field (boolean)
- `height` - Editor height (default: '200px')
- `help` - Help text below editor

**Features:**
- Rich text formatting (bold, italic, underline, strike)
- Headers (H1-H6)
- Lists (ordered and unordered)
- Colors and backgrounds
- Text alignment
- Links and images
- Automatic script and style injection

---

## Complete Form Example

```blade
@extends('layouts.main')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h4>Create Post</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('posts.store') }}" method="POST">
                        @csrf

                        <x-form.input 
                            name="title" 
                            label="Post Title"
                            placeholder="Enter post title"
                            required
                        />

                        <x-form.input 
                            type="url"
                            name="slug" 
                            label="Slug"
                            placeholder="post-slug"
                            help="URL-friendly version of the title"
                        />

                        <x-form.select 
                            name="category_id" 
                            label="Category"
                            :options="$categories"
                            placeholder="Select a category"
                            required
                        />

                        <x-form.select 
                            name="status" 
                            label="Status"
                            :options="['draft' => 'Draft', 'published' => 'Published']"
                            selected="draft"
                        />

                        <x-form.textarea 
                            name="excerpt" 
                            label="Excerpt"
                            rows="3"
                            placeholder="Brief description of the post"
                        />

                        <x-form.text-editor 
                            name="content" 
                            label="Content"
                            height="300px"
                            required
                        />

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="{{ route('posts.index') }}" class="btn btn-secondary">
                                Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                Create Post
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
```

## Validation Example

**Controller:**
```php
public function store(Request $request)
{
    $validated = $request->validate([
        'title' => 'required|string|max:255',
        'slug' => 'nullable|string|max:255|unique:posts',
        'category_id' => 'required|exists:categories,id',
        'status' => 'required|in:draft,published',
        'excerpt' => 'nullable|string|max:500',
        'content' => 'required|string',
    ]);

    // Create post...
}
```

When validation fails, errors automatically appear below each field with Bootstrap's `.is-invalid` styling.

## Features

✅ Automatic validation error display  
✅ Bootstrap 5 styling  
✅ Clean, reusable components  
✅ Old input value retention  
✅ Customizable with additional classes  
✅ Help text support  
✅ Required field indicators  
✅ Rich text editor with Quill.js  
✅ Support for arrays and Eloquent collections  

## Customization

You can add additional classes to any component:

```blade
<x-form.input 
    name="email" 
    label="Email"
    class="form-control-lg"
/>
```

Or override classes entirely:

```blade
<x-form.textarea 
    name="note" 
    label="Note"
    :attributes="['class' => 'my-custom-class']"
/>
```

## Notes

- All components automatically handle old input values after validation failures
- The text editor component uses `@once` to prevent multiple script/style inclusions
- Components are fully compatible with Laravel's validation system
- Error messages are displayed with Bootstrap's `.invalid-feedback` class