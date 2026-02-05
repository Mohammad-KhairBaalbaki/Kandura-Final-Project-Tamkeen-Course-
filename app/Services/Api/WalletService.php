<?php

namespace App\Services\Api;

use App\Enums\StatusEnum;
use App\Models\Payment;
use App\Models\User;
use App\Models\Wallet;
use App\Notifications\User\UserWalletNotification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class WalletService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function checkWalletPay(User $user, $amount)
    {
        return DB::transaction(function () use ($user, $amount) {

            $wallet = $user->wallet;
            if (!isset($wallet)) {
                $wallet = Wallet::create([
                    'user_id' => Auth::id(),
                    'balance' => 0
                ]);
            }
            if ($wallet->balance < $amount) {
                return false;
            }
            return true;

        });
    }

    public function payWallet(User $user, $amount)
    {
        return DB::transaction(function () use ($user, $amount) {

            $wallet = $user->wallet;
            $wallet->balance = $wallet->balance - $amount;
            $wallet->save();

            //send notification to user when balance is debited
            $user->notify(new UserWalletNotification(
                event: 'debited',
                amount: $amount,
                balance: $wallet->balance,

            ));
        });
    }


}


