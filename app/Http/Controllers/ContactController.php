<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    /**
     * Display a listing of contacts.
     */
    public function index(Request $request)
    {
        if (!auth('admin')->user()->is_super_admin) {
            abort(403, 'Unauthorized action.');
        }

        $query = Contact::query();

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Filter
        if ($request->filled('filter')) {
            if ($request->filter === 'read') {
                $query->whereNotNull('read_at');
            } elseif ($request->filter === 'unread') {
                $query->whereNull('read_at');
            }
        }

        $contacts = $query->latest()->paginate(10)->withQueryString();

        return view('admin.contacts.index', compact('contacts'));
    }

    /**
     * Toggle read status of a contact.
     */
    public function toggleRead(string $id)
    {
        if (!auth('admin')->user()->is_super_admin) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $contact = Contact::findOrFail($id);
        
        if ($contact->read_at) {
            $contact->update(['read_at' => null]);
        } else {
            $contact->update(['read_at' => now()]);
        }

        return response()->json([
            'success' => true,
            'read_at' => $contact->read_at ? $contact->read_at->format('Y-m-d H:i:s') : null,
            'is_read' => (bool)$contact->read_at,
        ]);
    }

    /**
     * Display the specified contact.
     */
    public function show(string $id)
    {
        if (!auth('admin')->user()->is_super_admin) {
            abort(403, 'Unauthorized action.');
        }

        $contact = Contact::findOrFail($id);

        if (!$contact->read_at) {
            $contact->update(['read_at' => now()]);
        }

        return view('admin.contacts.show', compact('contact'));
    }

    /**
     * Remove the specified contact from storage.
     */
    public function destroy(string $id)
    {
        if (!auth('admin')->user()->is_super_admin) {
            abort(403, 'Unauthorized action.');
        }

        $contact = Contact::findOrFail($id);
        $contact->delete();

        return redirect()->route('master.contacts.index')->with('success', 'Contact message deleted successfully.');
    }
}
