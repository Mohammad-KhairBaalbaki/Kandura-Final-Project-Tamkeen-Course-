<?php

namespace App\Services\Api;

use App\Enums\StatusEnum;
use App\Models\Order;
use App\Models\Review;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReviewService
{
    public function store(Order $order, array $data)
    {
        return DB::transaction(function () use ($order, $data) {
            

            if ($order->status !== StatusEnum::DELIVERED) {
                return '2';
            }

            if ((int) $order->user_id !== (int) Auth::id()) {
                return '3';
            }

            $exists = Review::where('order_id', $order->id)
                ->where('user_id', Auth::id())
                ->exists();
            if ($exists) {
                return '4';
            }
            $review = Review::create([
                'user_id' => Auth::id(),
                'order_id' => $order->id,
                'rating' => $data['rating'],
                'comment' => $data['comment'],
            ]);
            return $review;
        });
    }
}

