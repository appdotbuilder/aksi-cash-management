<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCapitalRequestRequest;
use App\Models\CapitalRequest;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CapitalRequestController extends Controller
{
    /**
     * Display a listing of capital requests.
     */
    public function index()
    {
        $user = auth()->user();
        $query = CapitalRequest::with(['outlet', 'operator', 'finance']);
        
        // Filter based on user role
        switch ($user->role) {
            case 'outlet':
                $query->where('outlet_user_id', $user->id);
                break;
            case 'operator':
                $query->whereIn('status', ['pending', 'operator_approved']);
                break;
            case 'finance':
                $query->whereIn('status', ['operator_approved', 'finance_approved', 'disbursed']);
                break;
        }
        
        $requests = $query->latest()->paginate(10);
        
        return Inertia::render('capital-requests/index', [
            'requests' => $requests,
            'user_role' => $user->role,
        ]);
    }

    /**
     * Show the form for creating a new capital request.
     */
    public function create()
    {
        return Inertia::render('capital-requests/create');
    }

    /**
     * Store a newly created capital request.
     */
    public function store(StoreCapitalRequestRequest $request)
    {
        $capitalRequest = CapitalRequest::create([
            'request_code' => CapitalRequest::generateRequestCode(),
            'outlet_user_id' => auth()->id(),
            'amount' => $request->amount,
            'purpose' => $request->purpose,
            'status' => 'pending',
        ]);

        return redirect()->route('capital-requests.show', $capitalRequest)
            ->with('success', 'Capital request created successfully.');
    }

    /**
     * Display the specified capital request.
     */
    public function show(CapitalRequest $capitalRequest)
    {
        $capitalRequest->load(['outlet', 'operator', 'finance']);
        
        return Inertia::render('capital-requests/show', [
            'request' => $capitalRequest,
            'user_role' => auth()->user()->role,
        ]);
    }

    /**
     * Update the specified capital request (for approvals).
     */
    public function update(Request $request, CapitalRequest $capitalRequest)
    {
        $user = auth()->user();
        $action = $request->input('action');
        
        switch ($action) {
            case 'operator_approve':
                if ($user->role !== 'operator' || $capitalRequest->status !== 'pending') {
                    abort(403);
                }
                
                $capitalRequest->update([
                    'status' => 'operator_approved',
                    'operator_user_id' => $user->id,
                    'operator_approved_at' => now(),
                    'operator_notes' => $request->input('notes'),
                ]);
                break;
                
            case 'finance_approve':
                if ($user->role !== 'finance' || $capitalRequest->status !== 'operator_approved') {
                    abort(403);
                }
                
                $capitalRequest->update([
                    'status' => 'finance_approved',
                    'finance_user_id' => $user->id,
                    'finance_approved_at' => now(),
                    'finance_notes' => $request->input('notes'),
                ]);
                break;
                
            case 'disburse':
                if ($user->role !== 'finance' || $capitalRequest->status !== 'finance_approved') {
                    abort(403);
                }
                
                $capitalRequest->update([
                    'status' => 'disbursed',
                    'disbursed_at' => now(),
                ]);
                break;
                
            case 'reject':
                if (!in_array($user->role, ['operator', 'finance'])) {
                    abort(403);
                }
                
                $capitalRequest->update([
                    'status' => 'rejected',
                    'rejected_at' => now(),
                    'rejection_reason' => $request->input('rejection_reason'),
                ]);
                break;
        }

        return redirect()->route('capital-requests.show', $capitalRequest)
            ->with('success', 'Capital request updated successfully.');
    }
}