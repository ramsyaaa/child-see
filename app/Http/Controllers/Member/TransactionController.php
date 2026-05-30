<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    public function index()
    {
        $transactions = Transaction::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('member.transactions.index', compact('transactions'));
    }

    public function show(Transaction $transaction)
    {
        if ($transaction->user_id != Auth::id()) {
            abort(403);
        }

        $transaction->load(['items.product', 'bankAccount']);

        return view('member.transactions.show', compact('transaction'));
    }
}

