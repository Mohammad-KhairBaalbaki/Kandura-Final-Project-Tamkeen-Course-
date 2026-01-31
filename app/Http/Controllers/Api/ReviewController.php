<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use App\Http\Requests\StoreReviewRequest;
use App\Http\Resources\ReviewResource;
use App\Models\Order;
use App\Services\Api\ReviewService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ReviewController extends Controller
{
    //
    protected $reviewService;

    public function __construct(ReviewService $reviewService)
    {
        $this->reviewService = $reviewService;
    }

    public function storeForOrder(StoreReviewRequest $request, Order $order)
    {
        try {
            $review = $this->reviewService->store($order, $request->validated());
            if ($review === '1') {
                return $this->success(false, "UnAuthorized", 401);
            } elseif ($review === '2') {
                return $this->success(false, "Order must be delivered to review", 422);
            } elseif ($review === '3') {
                return $this->success(false, "You can only review your own order", 403);
            } elseif ($review === '4') {
                return $this->success(false, "Review already exists for this order", 409);
            }

            return $this->success(ReviewResource::make($review), "Review created successfully .", 201);
        } catch (\Exception $e) {
            Log::error($e);
            Log::error($e->getMessage());
            return $this->success(false, 'process failed try again later', 422);
        }
    }

    
}


