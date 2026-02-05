<?php

namespace App\Services\Web;

use App\Enums\StatusEnum;
use App\Http\Requests\ChargeWalletRequest;
use App\Models\Payment;
use App\Models\User;
use App\Models\Wallet;
use App\Notifications\User\UserWalletNotification;
use App\Services\Api\WalletService as CoreWalletService;
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

            $this->creditWallet($user, $amount);

            return $user;
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

