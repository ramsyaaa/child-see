<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\BankAccount;
use Illuminate\Http\Request;

class BankAccountController extends Controller
{
    public function index()
    {
        $bankAccounts = BankAccount::orderBy('created_at', 'desc')->paginate(10);
        return view('superadmin.bank-accounts.index', compact('bankAccounts'));
    }

    public function create()
    {
        return view('superadmin.bank-accounts.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'bank_name' => 'required|string|max:255',
            'account_number' => 'required|string|max:255',
            'account_holder' => 'required|string|max:255',
            'branch' => 'nullable|string|max:255',
        ]);

        $bankAccount = new BankAccount();
        $bankAccount->bank_name = $request->bank_name;
        $bankAccount->account_number = $request->account_number;
        $bankAccount->account_holder = $request->account_holder;
        $bankAccount->branch = $request->branch;
        $bankAccount->is_active = $request->has('is_active');
        $bankAccount->save();

        return redirect()->route('superadmin.bank-accounts.index')
            ->with('success', 'Bank account created successfully!');
    }

    public function show(BankAccount $bankAccount)
    {
        return view('superadmin.bank-accounts.show', compact('bankAccount'));
    }

    public function edit(BankAccount $bankAccount)
    {
        return view('superadmin.bank-accounts.edit', compact('bankAccount'));
    }

    public function update(Request $request, BankAccount $bankAccount)
    {
        $request->validate([
            'bank_name' => 'required|string|max:255',
            'account_number' => 'required|string|max:255',
            'account_holder' => 'required|string|max:255',
            'branch' => 'nullable|string|max:255',
        ]);

        $bankAccount->bank_name = $request->bank_name;
        $bankAccount->account_number = $request->account_number;
        $bankAccount->account_holder = $request->account_holder;
        $bankAccount->branch = $request->branch;
        $bankAccount->is_active = $request->has('is_active');
        $bankAccount->save();

        return redirect()->route('superadmin.bank-accounts.index')
            ->with('success', 'Bank account updated successfully!');
    }

    public function destroy(BankAccount $bankAccount)
    {
        // Check if bank account is used in any transactions
        if ($bankAccount->transactions()->count() > 0) {
            return redirect()->route('superadmin.bank-accounts.index')
                ->with('error', 'Cannot delete bank account that has transactions!');
        }

        $bankAccount->delete();

        return redirect()->route('superadmin.bank-accounts.index')
            ->with('success', 'Bank account deleted successfully!');
    }
}

