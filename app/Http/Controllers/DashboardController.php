<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\CashDeposit;
use App\Models\CapitalRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    /**
     * Display the dashboard.
     */
    public function index()
    {
        $user = auth()->user();
        
        $stats = [
            'pending_deposits' => 0,
            'pending_capital_requests' => 0,
            'total_deposits' => 0,
            'total_capital_requests' => 0,
        ];

        // Role-specific data
        switch ($user->role) {
            case 'outlet':
                $stats['pending_deposits'] = $user->outletDeposits()->where('status', 'pending')->count();
                $stats['pending_capital_requests'] = $user->outletCapitalRequests()->where('status', 'pending')->count();
                $stats['total_deposits'] = $user->outletDeposits()->count();
                $stats['total_capital_requests'] = $user->outletCapitalRequests()->count();
                break;
                
            case 'sales':
                $stats['pending_deposits'] = CashDeposit::where('status', 'pending')->count();
                $stats['total_deposits'] = CashDeposit::count();
                break;
                
            case 'operator':
                $stats['pending_deposits'] = CashDeposit::where('status', 'sales_approved')->count();
                $stats['pending_capital_requests'] = CapitalRequest::where('status', 'pending')->count();
                $stats['total_deposits'] = CashDeposit::count();
                $stats['total_capital_requests'] = CapitalRequest::count();
                break;
                
            case 'finance':
                $stats['pending_deposits'] = CashDeposit::where('status', 'operator_approved')->count();
                $stats['pending_capital_requests'] = CapitalRequest::where('status', 'operator_approved')->count();
                $stats['total_deposits'] = CashDeposit::count();
                $stats['total_capital_requests'] = CapitalRequest::count();
                break;
                
            case 'admin':
                $stats['pending_deposits'] = CashDeposit::where('status', 'pending')->count();
                $stats['pending_capital_requests'] = CapitalRequest::where('status', 'pending')->count();
                $stats['total_deposits'] = CashDeposit::count();
                $stats['total_capital_requests'] = CapitalRequest::count();
                $stats['total_users'] = User::count();
                break;
        }

        return Inertia::render('dashboard', [
            'stats' => $stats,
            'user_role' => $user->role,
        ]);
    }
}