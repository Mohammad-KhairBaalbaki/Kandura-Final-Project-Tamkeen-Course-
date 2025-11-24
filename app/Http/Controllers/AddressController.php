<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAddressRequest;
use App\Http\Requests\UpdateAddressRequest;
use App\Http\Resources\AddressResource;
use App\Models\Address;
use App\Services\AddressService;
use Illuminate\Http\Request;

class AddressController extends Controller
{
    //

    protected $addressService;
    public function __construct(AddressService $addressService)
    {
        $this->addressService = $addressService;
    }

    public function index(Request $request)
    {
        $addresses = $this->addressService->index($request->all());
        return $this->success(AddressResource::collection($addresses), "Addresses Retrieved Successfully .", 200);
    }
    public function store(StoreAddressRequest $request)
    {
        $address = $this->addressService->store($request->validated());
        // dd($address);
        return $this->success(AddressResource::make($address), "Address Created Successfully .", 201);
    }
    public function update(UpdateAddressRequest $request,Address $address)
    {
        $address = $this->addressService->update($request->validated(),$address);
        return $this->success(AddressResource::make($address), "Address Updated Successfully .", 200);
    }
    public function destroy(Address $address)
    {
        $flag = $this->addressService->delete($address);
        if(!$flag){
            return $this->success(null, "Address Not Found .", 404);
        }
        return $this->success(null, "Address Deleted Successfully .", 200);

    }
}
