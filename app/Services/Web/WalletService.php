<?php

namespace App\Services\Web;

use App\Http\Requests\ChargeWalletRequest;
use App\Models\User;
use App\Models\Wallet;
use App\Services\Global\WalletService as CoreWalletService;
use Illuminate\Support\Facades\DB;

class WalletService
{
    protected $walletService;

    public function __construct(CoreWalletService $walletService)
    {
        $this->walletService = $walletService;
    }

    public function charge()
    {
        return DB::transaction(function () {
            return Wallet::with('user.image')->latest()->paginate(15);
        });
    }

    public function storeCharge(ChargeWalletRequest $request)
    {
        return DB::transaction(function () use ($request) {
            $identifier = $request->input('identifier');
            $amount = (float) $request->input('amount');

            if (filter_var($identifier, FILTER_VALIDATE_EMAIL)) {
                $user = User::where('email', $identifier)->first();
            } else {
                $user = User::where('phone', $identifier)->first();
            }

            if (!$user) {
                return [
                    'error' => 'user_not_found',
                ];
            }

            $this->walletService->creditWallet($user, $amount);

            return $user;
        });
    }
}

