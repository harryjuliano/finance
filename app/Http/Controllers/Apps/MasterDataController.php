<?php

namespace App\Http\Controllers\Apps;

use App\Http\Controllers\Controller;
use App\Http\Requests\MasterDataRequest;
use App\Models\MasterData;

class MasterDataController extends Controller
{
    public function index()
    {
        $masterData = MasterData::query()
            ->when(request()->search, function ($query) {
                $query->where('code', 'like', '%'. request()->search .'%')
                    ->orWhere('name', 'like', '%'. request()->search .'%')
                    ->orWhere('category', 'like', '%'. request()->search .'%');
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return inertia('Apps/CashManagement/MasterData/Index', [
            'masterData' => $masterData,
            'categories' => [
                'Company & Branch',
                'Department & Cost Center',
                'Cash/Bank Accounts',
                'Partners (Vendor, Customer, Employee)',
                'Transaction Categories',
                'Tax Master',
                'Document Types',
                'Approval Matrix',
                'Currency & Exchange Rate',
            ],
        ]);
    }

    public function store(MasterDataRequest $request)
    {
        MasterData::create($request->validated());

        return back();
    }

    public function update(MasterDataRequest $request, MasterData $master_datum)
    {
        $master_datum->update($request->validated());

        return back();
    }

    public function destroy(MasterData $master_datum)
    {
        $master_datum->delete();

        return back();
    }
}
