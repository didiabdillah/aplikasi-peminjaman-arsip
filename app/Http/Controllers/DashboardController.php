<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Archive;
use App\Models\Loan;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->isAdmin()) {
            // Admin dashboard data
            $data = [
                'total_archives' => Archive::count(),
                'available_archives' => Archive::where('status', 'available')->count(),
                'borrowed_archives' => Archive::where('status', 'borrowed')->count(),
                'total_loans' => Loan::count(),
                'active_loans' => Loan::where('status', 'borrowed')->count(),
                'overdue_loans' => Loan::overdue()->count(),
                'total_users' => User::where('role', 'peminjam')->count(),
                'recent_loans' => Loan::with(['user', 'archive'])
                    ->orderBy('created_at', 'desc')
                    ->limit(5)
                    ->get(),
            ];
        } else {
            // Peminjam dashboard data
            $data = [
                'my_active_loans' => Loan::byUser($user->id)->where('status', 'borrowed')->count(),
                'my_total_loans' => Loan::byUser($user->id)->count(),
                'my_overdue_loans' => Loan::byUser($user->id)->overdue()->count(),
                'recent_loans' => Loan::with(['archive'])
                    ->byUser($user->id)
                    ->orderBy('created_at', 'desc')
                    ->limit(5)
                    ->get(),
            ];
        }

        return view('dashboard', compact('data'));
    }
}
