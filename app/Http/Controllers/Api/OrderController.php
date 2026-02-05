<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use App\Http\Requests\StoreOrderRequest;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use App\Services\Api\OrderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    //

    protected $orderService;
    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }
    public function index()
    {
        try {
            $orders = $this->orderService->index();
            if ($orders === '1') {
                return $this->success(false, "UnAuthorized", 401);
            } elseif ($orders === '2') {
                return $this->success(false, "No Orders yet", 200);
            }
            return $this->success(OrderResource::collection($orders), "Orders Retrived Successfully .", 200);
        } catch (\Exception $e) {
            Log::error($e);
            Log::error($e->getMessage());
            return $this->success(false, 'process failed try again later', 422);
        }
    }

    public function store(StoreOrderRequest $request)
    {
        try {
            $order = $this->orderService->store($request->validated());
            if ($order === '1') {
                return $this->success(false, "UnAuthorized", statusCode: 401);
            } elseif ($order === '2') {
                return $this->success(false, "Cart is empty", 200);
            } elseif ($order === '3') {
                return $this->success(false, "Coupon is not applicable because you didnt meet the order limit . please change it in cart", 200);
            } elseif ($order === '4') {
                return $this->success(false, "Coupon not found or has been deactivated by admin", 400);

            } elseif ($order === '5') {
                return $this->success(false, "Coupon already applied", 400);

            } elseif ($order === '6') {
                return $this->success(false, "Coupon is expired", 200);
            }
            return $this->success(OrderResource::make($order), "Order Placed Successfully .", 201);
        } catch (\Exception $e) {
            Log::error($e);
            Log::error($e->getMessage());
            return $this->success(false, 'process failed try again later', 422);
        }
    }

    public function pay(Order $order)
    {
        try {
            $data = $this->orderService->pay($order);
            if ($data === '1') {
                return $this->success(false, "you cant pay for this action", 401);
            } elseif ($data === '2') {
                return $this->success(false, "you dont have enough money in wallet", 401);
            } elseif ($data === '3') {
                return $this->success(false, "coupon removed because its expired please re pay your order", 200);
            } elseif ($data instanceof Order) {
                return $this->success(OrderResource::make($data), "Order Payment Confirmed Successfully .", 200);
            }
            return $data;
        } catch (\Exception $e) {
            Log::error($e);
            Log::error($e->getMessage());
            return $this->success(false, 'process failed try again later', 422);
        }
    }

    public function successPayment(Order $order)
    {
        try {
            $this->orderService->successPayment($order);
            return $this->success(OrderResource::make($order), "Order Payment Confirmed Successfully .", 200);
        } catch (\Exception $e) {
            Log::error($e);
            Log::error($e->getMessage());
            return $this->success(false, 'process failed try again later', 422);
        }
    }

    public function failedPayment()
    {
        return $this->success(false, "Order Payment failed .", 200);
    }


    public function update(Order $order)
    {
        try {
            $order = $this->orderService->update($order);
            if ($order === '1') {
                return $this->success(false, "UnAuthorized", 401);
            } elseif ($order === '2') {
                return $this->success(false, "you cant do this option", 200);
            }
            return $this->success(OrderResource::make($order), "Order cancelled Successfully .", 200);
        } catch (\Exception $e) {
            Log::error($e);
            Log::error($e->getMessage());
            return $this->success(false, 'process failed try again later', 422);
        }
    }

}


