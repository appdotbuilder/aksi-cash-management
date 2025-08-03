<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCashDepositRequest;
use App\Models\CashDeposit;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CashDepositController extends Controller
{
    /**
     * Display a listing of cash deposits.
     */
    public function index()
    {
        $user = auth()->user();
        $query = CashDeposit::with(['outlet', 'sales', 'operator', 'depositor', 'finance']);
        
        // Filter based on user role
        switch ($user->role) {
            case 'outlet':
                $query->where('outlet_user_id', $user->id);
                break;
            case 'sales':
                $query->whereIn('status', ['pending', 'sales_approved']);
                break;
            case 'operator':
                $query->whereIn('status', ['sales_approved', 'operator_approved']);
                break;
            case 'finance':
                $query->whereIn('status', ['operator_approved', 'finance_approved']);
                break;
            case 'penyetor':
                $query->where('depositor_user_id', $user->id);
                break;
        }
        
        $deposits = $query->latest()->paginate(10);
        
        return Inertia::render('cash-deposits/index', [
            'deposits' => $deposits,
            'user_role' => $user->role,
        ]);
    }

    /**
     * Show the form for creating a new cash deposit.
     */
    public function create()
    {
        return Inertia::render('cash-deposits/create');
    }

    /**
     * Store a newly created cash deposit.
     */
    public function store(StoreCashDepositRequest $request)
    {
        $deposit = CashDeposit::create([
            'deposit_code' => CashDeposit::generateDepositCode(),
            'outlet_user_id' => auth()->id(),
            'amount' => $request->amount,
            'description' => $request->description,
            'status' => 'pending',
        ]);

        return redirect()->route('cash-deposits.show', $deposit)
            ->with('success', 'Cash deposit request created successfully.');
    }

    /**
     * Display the specified cash deposit.
     */
    public function show(CashDeposit $cashDeposit)
    {
        $cashDeposit->load(['outlet', 'sales', 'operator', 'depositor', 'finance']);
        
        return Inertia::render('cash-deposits/show', [
            'deposit' => $cashDeposit,
            'user_role' => auth()->user()->role,
        ]);
    }

    /**
     * Update the specified cash deposit (for approvals).
     */
    public function update(Request $request, CashDeposit $cashDeposit)
    {
        $user = auth()->user();
        $action = $request->input('action');
        
        switch ($action) {
            case 'sales_approve':
                if ($user->role !== 'sales' || $cashDeposit->status !== 'pending') {
                    abort(403);
                }
                
                $cashDeposit->update([
                    'status' => 'sales_approved',
                    'sales_user_id' => $user->id,
                    'sales_approved_at' => now(),
                    'sales_notes' => $request->input('notes'),
                ]);
                break;
                
            case 'operator_approve':
                if ($user->role !== 'operator' || $cashDeposit->status !== 'sales_approved') {
                    abort(403);
                }
                
                $depositor = User::byRole('penyetor')->active()->inRandomOrder()->first();
                
                $cashDeposit->update([
                    'status' => 'operator_approved',
                    'operator_user_id' => $user->id,
                    'operator_approved_at' => now(),
                    'operator_notes' => $request->input('notes'),
                    'depositor_user_id' => $depositor?->id,
                ]);
                break;
                
            case 'finance_approve':
                if ($user->role !== 'finance' || $cashDeposit->status !== 'operator_approved') {
                    abort(403);
                }
                
                $cashDeposit->update([
                    'status' => 'finance_approved',
                    'finance_user_id' => $user->id,
                    'finance_approved_at' => now(),
                    'finance_notes' => $request->input('notes'),
                ]);
                break;
                
            case 'reject':
                if (!in_array($user->role, ['sales', 'operator', 'finance'])) {
                    abort(403);
                }
                
                $cashDeposit->update([
                    'status' => 'rejected',
                    'rejected_at' => now(),
                    'rejection_reason' => $request->input('rejection_reason'),
                ]);
                break;
        }

        return redirect()->route('cash-deposits.show', $cashDeposit)
            ->with('success', 'Cash deposit updated successfully.');
    }
}