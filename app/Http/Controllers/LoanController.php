<?php

namespace App\Http\Controllers;

use App\Models\Loan;
use App\Models\Archive;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class LoanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Loan::with(['user', 'archive', 'approver']);

        // Filter by status
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        // Filter by user (for non-admin users, show only their loans)
        if (!Auth::user()->isAdmin()) {
            $query->byUser(Auth::id());
        } elseif ($request->has('user_id') && $request->user_id) {
            $query->byUser($request->user_id);
        }

        // Filter overdue loans
        if ($request->has('overdue') && $request->overdue) {
            $query->overdue();
        }

        $loans = $query->orderBy('created_at', 'desc')->paginate(10);
        $users = User::where('role', 'peminjam')->get();

        return view('loans.index', compact('loans', 'users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $archives = Archive::available()->get();
        $users = Auth::user()->isAdmin() ? User::where('role', 'peminjam')->get() : collect([Auth::user()]);

        return view('loans.create', compact('archives', 'users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'archive_id' => 'required|exists:archives,id',
            'loan_date' => 'required|date',
            'due_date' => 'required|date|after:loan_date',
            'purpose' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        // Check if archive is available
        $archive = Archive::findOrFail($validated['archive_id']);

        if (!$archive->isAvailable()) {
            return redirect()->back()
                ->with('error', 'Arsip tidak tersedia untuk dipinjam.')->withInput();
        }

        // For non-admin users, they can only create loans for themselves
        if (!Auth::user()->isAdmin()) {
            $validated['user_id'] = Auth::id();
        }

        $validated['status'] = 'borrowed';

        // If user is admin, auto-approve the loan
        if (Auth::user()->isAdmin()) {
            $validated['approved_by'] = Auth::id();
            $validated['approved_at'] = Carbon::now();
        }

        $loan = Loan::create($validated);

        // Update archive status
        $archive->update(['status' => 'borrowed']);

        return redirect()->route('loans.index')
            ->with('success', 'Peminjaman berhasil dicatat.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Loan $loan)
    {
        $loan->load(['user', 'archive', 'approver']);

        return view('loans.show', compact('loan'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Loan $loan)
    {
        // Only allow editing if loan is not returned
        if ($loan->isReturned()) {
            return redirect()->route('loans.index')
            ->with('error', 'Peminjaman yang sudah dikembalikan tidak dapat diedit.');
        }

        $archives = Archive::where('id', $loan->archive_id)
            ->orWhere('status', 'available')
            ->get();

        $users = Auth::user()->isAdmin() ? User::where('role', 'peminjam')->get() : collect([Auth::user()]);

        return view('loans.edit', compact('loan', 'archives', 'users'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Loan $loan)
    {
        $validated = $request->validate([
            'due_date' => 'required|date|after:loan_date',
            'purpose' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $loan->update($validated);

        return redirect()->route('loans.index')
            ->with('success', 'Peminjaman berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Loan $loan)
    {
        // Only allow deletion if loan is not active
        if ($loan->status === 'borrowed') {
            return redirect()->route('loans.index')
                ->with('error', 'Peminjaman aktif tidak dapat dihapus.');
        }

        // If deleting a returned loan, make sure archive status is correct
        if ($loan->status === 'returned') {
            $loan->archive->update(['status' => 'available']);
        }

        $loan->delete();
        
        return redirect()->route('loans.index')
            ->with('success', 'Data peminjaman berhasil dihapus.');
    }

    /**
    * Process return of borrowed archive.
    */
    public function return(Request $request, Loan $loan)
    {
        if ($loan->isReturned()) {
            return redirect()->route('loans.index')
                ->with('error', 'Arsip sudah dikembalikan sebelumnya.');
        }

        $validated = $request->validate([
            'return_notes' => 'nullable|string',
        ]);

        $loan->update([
            'return_date' => Carbon::now()->toDateString(),
            'status' => 'returned',
            'notes' => $loan->notes . "\n\nCatatan Pengembalian: " . ($validated['return_notes'] ?? ''),
        ]);

        // Update archive status back to available
        $loan->archive->update(['status' => 'available']);

        return redirect()->route('loans.index')
            ->with('success', 'Arsip berhasil dikembalikan.');
    }

    /**
    * Approve a loan (for admin only).
    */
    public function approve(Loan $loan)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403);
        }
        if ($loan->approved_at) {
            return redirect()->route('loans.index')
                ->with('error', 'Peminjaman sudah disetujui sebelumnya.');
        }
        $loan->update([
            'approved_by' => Auth::id(),
            'approved_at' => Carbon::now(),
        ]);

        return redirect()->route('loans.index')
            ->with('success', 'Peminjaman berhasil disetujui.');
    }

    /**
    * Check archive availability before creating loan
    */
    public function checkAvailability(Request $request)
    {
        $archiveId = $request->input('archive_id');
        $archive = Archive::find($archiveId);

        if (!$archive) {
            return response()->json([
                'available' => false,
                'message' => 'Arsip tidak ditemukan'
            ]);
        }

        if (!$archive->isAvailable()) {
            $currentLoan = $archive->currentLoan;
            return response()->json([
                'available' => false,
                'message' => 'Arsip sedang dipinjam oleh ' . $currentLoan->user->name,
                'due_date' => $currentLoan->due_date->format('d/m/Y')
            ]);
        }

        return response()->json([
            'available' => true,
            'archive' => [
                'id' => $archive->id,
                'code' => $archive->code,
                'title' => $archive->title,
                'location' => $archive->location,
                'condition' => $archive->condition
            ]
        ]);
    }

    /**
    * Get loan statistics for dashboard
    */
    public function getStatistics()
    {
        $stats = [
            'total_loans' => Loan::count(),
            'active_loans' => Loan::where('status', 'borrowed')->count(),
            'overdue_loans' => Loan::overdue()->count(),
            'returned_loans' => Loan::where('status', 'returned')->count(),
            'loans_this_month' => Loan::whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count(),
            'popular_archives' => Archive::withCount(['loans' => function($query) {
                $query->whereMonth('created_at', now()->month);
            }])
                ->orderBy('loans_count', 'desc')
                ->limit(5)
                ->get()
        ];

        return response()->json($stats);
    }

    /**
    * Extend loan due date
    */
    public function extend(Request $request, Loan $loan)
    {
        if ($loan->isReturned()) {
            return redirect()->back()
                ->with('error', 'Peminjaman yang sudah dikembalikan tidak dapat diperpanjang.');
        }

        $validated = $request->validate([
            'new_due_date' => 'required|date|after:' . $loan->due_date,
            'extension_reason' => 'required|string|max:255'
        ]);

        $oldDueDate = $loan->due_date;
        $loan->update([
            'due_date' => $validated['new_due_date'],
            'notes' => $loan->notes . "\n\nPerpanjangan: " . $oldDueDate->format('d/m/Y') . " → " . Carbon::parse($validated['new_due_date'])->format('d/m/Y') . "\nAlasan: " . $validated['extension_reason']
        ]);
        
        return redirect()->back()
            ->with('success', 'Jatuh tempo peminjaman berhasil diperpanjang.');
    }
}