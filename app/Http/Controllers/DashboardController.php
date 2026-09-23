<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Data dummy/akurat untuk dashboard
        $stats = [
            'active_loans' => 2,
            'due_soon' => 1,
            'total_fines' => 'Rp 15.000',
            'books_read' => 12,
            'history_total' => 14,
            'history_ontime' => 11,
            'history_late' => 3,
        ];

        return view('user.dashboard', compact('user', 'stats'));
    }
}