<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\ChargeWalletRequest;
use App\Services\Web\WalletService;
use Illuminate\Support\Facades\Log;

class WalletController extends Controller
{
    protected $walletService;

    public function __construct(WalletService $walletService)
    {
        $this->walletService = $walletService;
    }

    public function charge()
    {
        try {
            $wallets = $this->walletService->charge();
            return view('wallets.charge', compact('wallets'));
        } catch (\Exception $e) {
            Log::error($e);
            Log::error($e->getMessage());
            return $this->success(false, 'process failed try again later', 422);
        }
    }

    public function storeCharge(ChargeWalletRequest $request)
    {
        try {
            $result = $this->walletService->storeCharge($request);
            if (is_array($result) && ($result['error'] ?? null) === 'user_not_found') {
                return back()
                    ->withInput()
                    ->withErrors(['identifier' => __('wallets.user_not_found')]);
            }

            return back()->with('success', __('wallets.wallet_charged'));
        } catch (\Exception $e) {
            Log::error($e);
            Log::error($e->getMessage());
            return $this->success(false, 'process failed try again later', 422);
        }
    }
}
