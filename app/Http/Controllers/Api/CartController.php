<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCouponRequest;
use App\Http\Requests\StoreItemInCartRequest;
use App\Http\Requests\UpdateItemInCartRequest;
use App\Http\Requests\UseCouponRequest;
use App\Http\Resources\CartResource;
use App\Http\Resources\ItemCartResource;
use App\Models\ItemCart;
use App\Services\Api\CartService;
use Illuminate\Support\Facades\Log;

class CartController extends Controller
{
    //

    protected $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    public function index()
    {
        try {
            $cart = $this->cartService->index();
            if ($cart === '1') {
                return $this->success(false, 'Cart is empty', 200);
            }

            return $this->success(CartResource::make($cart), 'Cart Retrived Successfully .', 201);
        } catch (\Exception $e) {
            Log::error($e);
            Log::error($e->getMessage());

            return $this->success(false, 'process failed try again later', 422);
        }
    }

    public function store(StoreItemInCartRequest $request)
    {
        try {
            $item = $this->cartService->store($request->validated());
            if ($item === '1') {
                return $this->success(false, 'UnAuthorized', 401);
            }

            return $this->success(ItemCartResource::make($item), 'Item Added To Cart Successfully .', 201);
        } catch (\Exception $e) {
            Log::error($e);
            Log::error($e->getMessage());

            return $this->success(false, 'process failed try again later', 422);
        }
    }

    public function addCoupon(UseCouponRequest $request)
    {
        try {
            $data = $this->cartService->addCoupon($request->validated());
            if ($data === '1') {
                return $this->success(false, 'UnAuthorized', 401);
            } elseif ($data === '2') {
                return $this->success(false, 'Coupon not found or has been deactivated by admin', 400);
            } elseif ($data === '3') {
                return $this->success(false, 'Coupon already applied', 400);
            } elseif ($data === '4') {
                return $this->success(false, 'Coupon is expired', 200);
            } elseif ($data === '5') {
                return $this->success(false, 'Coupon is not applicable because you didnt meet the order limit', 200);
            }

            return $this->success(CartResource::make($data), 'Coupon Added Successfully .', 200);
        } catch (\Exception $e) {
            Log::error($e);
            Log::error($e->getMessage());

            return $this->success(false, 'process failed try again later', 422);
        }
    }

    public function removeCoupon()
    {
        try {
            $data = $this->cartService->removeCoupon();
            if ($data === '1') {
                return $this->success(false, 'UnAuthorized', 401);
            }
            if ($data === '2') {
                return $this->success(false, 'you dont have coupon in your cart', 200);
            }

            return $this->success(CartResource::make($data), 'Coupon Removed Successfully .', 200);
        } catch (\Exception $e) {
            Log::error($e);
            Log::error($e->getMessage());

            return $this->success(false, 'process failed try again later', 422);
        }
    }

    public function update(UpdateItemInCartRequest $request, ItemCart $item)
    {
        try {
            $item = $this->cartService->update($request->validated(), $item);
            if ($item === '1') {
                return $this->success(false, 'UnAuthorized', 401);
            }

            return $this->success(ItemCartResource::make($item), 'Item Edited In Cart Successfully .', 200);
        } catch (\Exception $e) {
            Log::error($e);
            Log::error($e->getMessage());

            return $this->success(false, 'process failed try again later', 422);
        }
    }

    public function destroy(ItemCart $item)
    {
        try {
            $data = $this->cartService->delete($item);
            if ($data === '1') {
                return $this->success(false, 'UnAuthorized', 401);
            }

            return $this->success(($data), 'Item deleted from Cart Successfully .', 200);
        } catch (\Exception $e) {
            Log::error($e);
            Log::error($e->getMessage());

            return $this->success(false, 'process failed try again later', 422);
        }
    }
}
