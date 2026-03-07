<?php

namespace App\Http\Controllers\Apps;

use App\Http\Controllers\Controller;
use App\Http\Requests\TransactionRequest;
use App\Models\Transaction;

class TransactionController extends Controller
{
    public function index()
    {
        $transactions = Transaction::query()
            ->when(request()->search, function ($query) {
                $query->where('reference_no', 'like', '%'.request()->search.'%')
                    ->orWhere('description', 'like', '%'.request()->search.'%')
                    ->orWhere('type', 'like', '%'.request()->search.'%')
                    ->orWhere('status', 'like', '%'.request()->search.'%');
            })
            ->latest('transaction_date')
            ->latest('id')
            ->paginate(10)
            ->withQueryString();

        return inertia('Apps/CashManagement/Transactions/Index', [
            'transactions' => $transactions,
            'types' => [
                'Payment Request',
                'Cash In',
                'Cash Out',
                'Petty Cash',
                'Transfer Antar Akun',
                'Adjustment / Reversal',
                'Recurring',
            ],
            'statuses' => [
                'Draft',
                'Submitted',
                'Verified',
                'Approved',
                'Paid/Received',
                'Posted',
                'Rejected',
                'Cancelled',
            ],
        ]);
    }

    public function store(TransactionRequest $request)
    {
        Transaction::create($request->validated());

        return back();
    }

    public function update(TransactionRequest $request, Transaction $transaction)
    {
        $transaction->update($request->validated());

        return back();
    }

    public function destroy(Transaction $transaction)
    {
        $transaction->delete();

        return back();
    }
}
