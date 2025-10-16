<?php

namespace App\Http\Controllers;

use App\Models\Wallet;
use App\Services\WalletPaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WalletController extends Controller
{
    protected $paymentService;

    public function __construct(WalletPaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    public function showDepositForm()
    {
        return view('wallet.deposit');
    }

    public function deposit(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1000|max:10000000',
        ]);

        $wallet = auth()->user()->wallet;
        $amount = $request->amount;

        $result = $this->paymentService->createPaymentRequest(
            $wallet, 
            $amount,
            "افزایش موجودی به مبلغ " . number_format($amount) . " تومان"
        );

        if ($result['success']) {
            return redirect($result['payment_url']);
        }

        return redirect()->route('wallet.deposit')
            ->with('error', $result['message'])
            ->withInput();
    }

    public function verifyPayment(Request $request)
    {
        $authority = $request->input('Authority');
        $status = $request->input('Status');

        if ($status != 'OK') {
            return redirect()->route('dashboard')
                ->with('error', 'پرداخت لغو شد');
        }

        $result = $this->paymentService->verifyPayment($authority);

        if ($result['success']) {
            return redirect()->route('dashboard')
                ->with('success', $result['message'])
                ->with('ref_id', $result['ref_id']);
        }

        return redirect()->route('dashboard')
            ->with('error', $result['message']);
    }
}