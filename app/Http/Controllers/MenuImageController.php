<?php

namespace App\Http\Controllers;

use App\Models\MenuImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class MenuImageController extends Controller
{
    /**
     * Display a listing of the images and PDFs.
     */
    public function index()
    {
        $gallery = MenuImage::orderBy('sort_order', 'asc')->orderBy('created_at', 'desc')->get();
        return view('admin.pages.menu-images.index', compact('gallery'));
    }

    /**
     * Store a newly created image or PDF in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'files.*' => 'nullable|file|mimes:jpeg,png,jpg,gif,pdf|max:5120',
            'external_url' => 'nullable|url'
        ], [
            'files.*.max' => 'Each image or PDF must be less than 5 MB.',
            'files.*.mimes' => 'Only JPEG, PNG, JPG, GIF, and PDF files are allowed.'
        ]);

        $adminId = Auth::guard('admin')->id();
        $mediaAdded = false;

        // Handle File Uploads
        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                $path = $file->store('menu-gallery', 'public');
                $type = strtolower($file->getClientOriginalExtension()) === 'pdf' ? 'pdf' : 'image';

                MenuImage::create([
                    'media_path' => $path,
                    'media_type' => $type,
                    'media_source' => 'local',
                    'admin_id' => $adminId,
                    'is_active' => true,
                    'sort_order' => 0
                ]);
                $mediaAdded = true;
            }
        }

        // Handle External URL
        if ($request->filled('external_url')) {
            $url = $request->external_url;
            $extension = strtolower(pathinfo(parse_url($url, PHP_URL_PATH), PATHINFO_EXTENSION));
            $type = in_array($extension, ['pdf']) ? 'pdf' : 'image';

            MenuImage::create([
                'media_path' => $url,
                'media_type' => $type,
                'media_source' => 'external',
                'admin_id' => $adminId,
                'is_active' => true,
                'sort_order' => 0
            ]);
            $mediaAdded = true;
        }

        if ($mediaAdded) {
            return back()->with('success', 'Media added successfully!');
        }

        return back()->with('error', 'No files or external URL provided.');
    }

    /**
     * Update status (active/inactive)
     */
    public function updateStatus(Request $request, $id)
    {
        $gallery = MenuImage::findOrFail($id);
        $gallery->update(['is_active' => ! $gallery->is_active]);
        
        return response()->json(['success' => true, 'is_active' => $gallery->is_active]);
    }

    /**
     * Update sort order
     */
    public function updateOrder(Request $request, $id)
    {
        $request->validate(['sort_order' => 'required|integer']);
        $gallery = MenuImage::findOrFail($id);
        $gallery->update(['sort_order' => $request->sort_order]);

        return response()->json(['success' => true]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $gallery = MenuImage::findOrFail($id);

        if ($gallery->media_source === 'local') {
            Storage::disk('public')->delete($gallery->media_path);
        }

        $gallery->delete();

        return back()->with('success', 'Item removed from gallery.');
    }
}
