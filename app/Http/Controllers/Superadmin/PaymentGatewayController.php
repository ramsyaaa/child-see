<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\PaymentGatewayConfig;
use App\Models\BankAccount;
use Illuminate\Http\Request;

class PaymentGatewayController extends Controller
{
    public function index()
    {
        $config       = PaymentGatewayConfig::getConfig();
        $bankAccounts = BankAccount::orderBy('bank_name')->get();

        return view('superadmin.payment-gateway.index', compact('config', 'bankAccounts'));
    }

    public function update(Request $request)
    {
        $config = PaymentGatewayConfig::getConfig();

        $config->update([
            'offline_enabled' => $request->boolean('offline_enabled'),
            'mayar_enabled'   => $request->boolean('mayar_enabled'),
        ]);

        return redirect()->route('superadmin.payment-gateway')
            ->with('success', 'Payment gateway configuration updated successfully.');
    }
}
