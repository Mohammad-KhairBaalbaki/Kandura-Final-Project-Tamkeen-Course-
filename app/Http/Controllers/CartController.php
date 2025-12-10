<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreItemInCartRequest;
use App\Http\Resources\ItemCartResource;
use App\Services\CartService;
use Illuminate\Http\Request;

class CartController extends Controller
{
    //

    protected $cartService;
    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }
    public function index(){
    }

    public function store(StoreItemInCartRequest $request){
        $item = $this->cartService->store($request->validated());
        return $this->success(ItemCartResource::make($item), "Item Added To Cart Successfully .", 201);
    }

    public function update(){
    }

    public function delete(){
    }

}
