# Image Cropper Component

Advanced image cropper component for Laravel 12 with Bootstrap 5, featuring aspect ratio support, image preview, and optimized performance using Cropper.js.

## Features

✅ **Aspect Ratio Control** - Crop images to specific dimensions  
✅ **Live Preview** - See cropped image immediately  
✅ **Base64 Storage** - Stores cropped image as base64 in hidden input  
✅ **Validation Support** - Integrates with Laravel validation  
✅ **Remove Functionality** - Easy image removal  
✅ **Optimized Performance** - Uses `@once` directive to prevent duplicate scripts  
✅ **Responsive Modal** - Bootstrap modal for cropping interface  
✅ **Image Quality Control** - Adjustable compression settings  
✅ **OOP JavaScript** - Clean, reusable ImageCropper class  

## Installation

1. **Copy the component:**
   ```
   resources/views/components/form/cropper.blade.php
   ```

2. **Ensure Bootstrap Icons (optional for remove button):**
   ```html
   <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
   ```
   
   Or replace `<i class="bi bi-x-lg"></i>` with an × symbol in the component.

## Basic Usage

```blade
<x-form.cropper 
    name="avatar" 
    label="Profile Picture"
    :aspectRatio="1"
/>
```

## Advanced Usage Examples

### Product Image (16:9)
```blade
<x-form.cropper 
    name="product_image" 
    label="Product Image"
    :aspectRatio="16/9"
    previewWidth="100%"
    previewHeight="400px"
    help="Upload a high-quality product image (16:9 ratio)"
    required
/>
```

### Square Profile Picture (1:1)
```blade
<x-form.cropper 
    name="avatar" 
    label="Avatar"
    :aspectRatio="1"
    previewWidth="200px"
    previewHeight="200px"
    :maxWidth="500"
    :maxHeight="500"
    :quality="0.95"
/>
```

### Banner Image (21:9)
```blade
<x-form.cropper 
    name="banner" 
    label="Website Banner"
    :aspectRatio="21/9"
    previewHeight="300px"
    :maxWidth="2560"
    :maxHeight="1097"
/>
```

### Instagram Post (4:5)
```blade
<x-form.cropper 
    name="instagram_image" 
    label="Instagram Image"
    :aspectRatio="4/5"
    previewWidth="400px"
    previewHeight="500px"
/>
```

### Edit Mode with Existing Image
```blade
<x-form.cropper 
    name="profile_photo" 
    label="Profile Photo"
    :aspectRatio="1"
    value="{{ $user->profile_photo }}"
/>
```

## Props Reference

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `name` | string | **required** | Input name for form submission |
| `label` | string | '' | Label text above input |
| `value` | string | '' | Existing image (base64 or URL) |
| `aspectRatio` | float | 16/9 | Crop aspect ratio (width/height) |
| `required` | boolean | false | Mark field as required |
| `help` | string | '' | Help text below input |
| `previewWidth` | string | '100%' | Preview container width |
| `previewHeight` | string | '300px' | Preview container height |
| `maxWidth` | integer | 1920 | Maximum output width in pixels |
| `maxHeight` | integer | 1080 | Maximum output height in pixels |
| `quality` | float | 0.9 | JPEG quality (0.0 - 1.0) |

## Common Aspect Ratios

```blade
{{-- Square (Profile Pictures) --}}
:aspectRatio="1"      {{-- 1:1 --}}

{{-- Landscape --}}
:aspectRatio="16/9"   {{-- Widescreen --}}
:aspectRatio="4/3"    {{-- Standard --}}
:aspectRatio="21/9"   {{-- Ultrawide --}}
:aspectRatio="3/2"    {{-- Classic photo --}}

{{-- Portrait --}}
:aspectRatio="9/16"   {{-- Vertical video --}}
:aspectRatio="4/5"    {{-- Instagram portrait --}}
:aspectRatio="2/3"    {{-- Portrait photo --}}

{{-- Social Media --}}
:aspectRatio="1.91"   {{-- Facebook link --}}
:aspectRatio="1200/630" {{-- Open Graph --}}
```

## Complete Form Example

```blade
@extends('layouts.main')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4>Edit Profile</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('profile.update') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <x-form.input 
                            name="name" 
                            label="Full Name"
                            value="{{ $user->name }}"
                            required
                        />

                        <x-form.cropper 
                            name="avatar" 
                            label="Profile Picture"
                            :aspectRatio="1"
                            previewWidth="200px"
                            previewHeight="200px"
                            value="{{ $user->avatar }}"
                            help="Upload a square image for your profile"
                        />

                        <x-form.cropper 
                            name="cover_photo" 
                            label="Cover Photo"
                            :aspectRatio="16/9"
                            previewHeight="300px"
                            value="{{ $user->cover_photo }}"
                            help="Upload a banner image (16:9 ratio recommended)"
                        />

                        <x-form.textarea 
                            name="bio" 
                            label="Bio"
                            value="{{ $user->bio }}"
                            rows="4"
                        />

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('profile.show') }}" class="btn btn-secondary">
                                Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                Save Changes
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

## Backend Processing

### Controller - Validation
```php
public function update(Request $request)
{
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'avatar' => 'nullable|string',  // base64 string
        'cover_photo' => 'nullable|string',
        'bio' => 'nullable|string|max:500',
    ]);

    // Process base64 images
    if ($request->filled('avatar')) {
        $validated['avatar'] = $this->saveBase64Image($request->avatar, 'avatars');
    }

    if ($request->filled('cover_photo')) {
        $validated['cover_photo'] = $this->saveBase64Image($request->cover_photo, 'covers');
    }

    auth()->user()->update($validated);

    return redirect()->route('profile.show')->with('success', 'Profile updated!');
}
```

### Helper Method - Save Base64 to Storage
```php
private function saveBase64Image($base64String, $folder = 'images')
{
    // Extract the base64 encoded image
    if (preg_match('/^data:image\/(\w+);base64,/', $base64String, $type)) {
        $data = substr($base64String, strpos($base64String, ',') + 1);
        $type = strtolower($type[1]); // jpg, png, gif

        $data = base64_decode($data);

        if ($data === false) {
            throw new \Exception('Base64 decode failed');
        }

        $fileName = uniqid() . '.' . $type;
        $path = "{$folder}/{$fileName}";

        Storage::disk('public')->put($path, $data);

        return "/storage/{$path}";
    }

    return null;
}
```

### Alternative - Using Intervention Image
```php
use Intervention\Image\Facades\Image;

private function saveBase64Image($base64String, $folder = 'images')
{
    $image = Image::make($base64String);
    
    $fileName = uniqid() . '.jpg';
    $path = "{$folder}/{$fileName}";
    
    Storage::disk('public')->put($path, $image->encode('jpg', 90));
    
    return "/storage/{$path}";
}
```

## Optimization Tips

### 1. Reduce File Size
```blade
{{-- Lower quality for thumbnails --}}
<x-form.cropper 
    name="thumbnail" 
    :quality="0.7"
    :maxWidth="800"
    :maxHeight="600"
/>
```

### 2. Specific Dimensions
```blade
{{-- Exact size for icons --}}
<x-form.cropper 
    name="icon" 
    :aspectRatio="1"
    :maxWidth="256"
    :maxHeight="256"
    :quality="1"
/>
```

### 3. High-Quality Images
```blade
{{-- For print or large displays --}}
<x-form.cropper 
    name="hero_image" 
    :maxWidth="3840"
    :maxHeight="2160"
    :quality="0.95"
/>
```

## JavaScript API

The component uses an `ImageCropper` class with the following methods:

```javascript
// Access cropper instance (for advanced use)
const cropper = document.getElementById('file-avatar').cropperInstance;

// Methods available:
cropper.handleFileSelect(event)  // Process file selection
cropper.initCropper()            // Initialize Cropper.js
cropper.handleCrop()             // Crop and save image
cropper.removeImage()            // Remove current image
cropper.destroyCropper()         // Cleanup cropper instance
cropper.loadExistingImage()      // Load existing image
```

## Browser Compatibility

- Chrome/Edge: ✅ Full support
- Firefox: ✅ Full support  
- Safari: ✅ Full support
- Opera: ✅ Full support

Requires:
- FileReader API
- Canvas API
- Bootstrap 5
- ES6 Classes

## Troubleshooting

### Image not showing in preview
- Ensure the value prop contains valid base64 data or image URL
- Check browser console for errors

### Cropper not initializing
- Verify Bootstrap JS is loaded before component scripts
- Check that modal ID doesn't conflict with other modals

### Large file sizes
- Reduce `quality` prop (e.g., 0.7 instead of 0.9)
- Lower `maxWidth` and `maxHeight` values
- Use JPEG instead of PNG for photos

### Validation errors not showing
- Ensure validation rule matches the input name
- Check that `$errors` bag is available in the view

## Notes

- The component stores images as **base64 strings** in the hidden input
- Cropped images are converted to **JPEG format** for optimal file size
- Uses **@once** directive to prevent duplicate script loading
- Modal uses `data-bs-backdrop="static"` to prevent accidental closure
- Remove button requires Bootstrap Icons or can be customized

## Security Considerations

When processing uploaded images:
- Validate MIME types on the server
- Limit file sizes (Laravel's `max:10240` = 10MB)
- Store images outside the web root when possible
- Sanitize filenames
- Consider using signed URLs for sensitive images

```php
$request->validate([
    'avatar' => 'required|string|max:10240', // 10MB in KB
]);
```