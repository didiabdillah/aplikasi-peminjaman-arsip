<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Archive;

class ArchiveController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Archive::with('creator');

        // Search functionality
        if ($request->has('search') && $request->search) {
            $query->search($request->search);
        }

        // Filter by category
        if ($request->has('category') && $request->category) {
            $query->byCategory($request->category);
        }

        // Filter by status
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        $archives = $query->paginate(10);
        $categories = Archive::distinct()->pluck('category')->filter();
        return view('archives.index', compact('archives', 'categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('archives.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:50|unique:archives',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'nullable|string|max:100',
            'location' => 'required|string|max:255',
            'shelf_number' => 'nullable|string|max:50',
            'box_number' => 'nullable|string|max:50',
            'year' => 'nullable|integer|min:1900|max:' . date('Y'),
            'condition' => 'required|in:good,fair,poor',
        ]);

        $validated['created_by'] = Auth::id();
        $validated['status'] = 'available';

        Archive::create($validated);

        return redirect()->route('archives.index')
            ->with('success', 'Arsip berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Archive $archive)
    {
        $archive->load(['creator', 'loans.user']);
        return view('archives.show', compact('archive'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Archive $archive)
    {
        return view('archives.edit', compact('archive'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Archive $archive)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:50|unique:archives,code,' . $archive->id,
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'nullable|string|max:100',
            'location' => 'required|string|max:255',
            'shelf_number' => 'nullable|string|max:50',
            'box_number' => 'nullable|string|max:50',
            'year' => 'nullable|integer|min:1900|max:' . date('Y'),
            'status' => 'required|in:available,borrowed,maintenance',
            'condition' => 'required|in:good,fair,poor',
        ]);

        $archive->update($validated);

        return redirect()->route('archives.index')
            ->with('success', 'Arsip berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Archive $archive)
    {
        // Check if archive has active loans
        if ($archive->loans()->where('status', 'borrowed')->exists()) {
            return redirect()->route('archives.index')
                ->with('error', 'Arsip tidak dapat dihapus karena sedang dipinjam.');
        }

        $archive->delete();

        return redirect()->route('archives.index')
            ->with('success', 'Arsip berhasil dihapus.');
    }

    /**
    * Get archives for AJAX requests
    */
    public function search(Request $request)
    {
        $query = Archive::available();

        if ($request->has('q') && $request->q) {
            $query->search($request->q);
        }

        $archives = $query->select('id', 'code', 'title', 'location')
            ->limit(10)
            ->get();
            
        return response()->json($archives);
    }
}
