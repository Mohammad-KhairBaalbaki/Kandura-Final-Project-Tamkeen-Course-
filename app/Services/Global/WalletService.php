<?php

namespace App\Services\Global;

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

    public function creditWallet(User $user, $amount)
    {
        return DB::transaction(function () use ($user, $amount) {

            $wallet = $user->wallet;
            if (!isset($wallet)) {
                $wallet = Wallet::create([
                    'user_id' => $user->id,
                    'balance' => 0,
                ]);
            }
            $wallet->balance = $wallet->balance + $amount;
            $wallet->save();



            //send notification to user when balance is credited
            $user->notify(new UserWalletNotification(
                event: 'credited',
                amount: $amount,
                balance: $wallet->balance,

            ));


            $payment = Payment::create([
                'user_id' => $user->id,
                'method' => 'wallet',
                'status' => StatusEnum::CONFIRMED,
                'amount' => $amount,
                'type' => 'charge',
            ]);

            $payment->num = $payment->created_at->format('Ymd') . $payment->id;
            $payment->save();


            return $wallet;
        });
    }
}


