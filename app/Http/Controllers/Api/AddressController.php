<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use App\Http\Requests\StoreAddressRequest;
use App\Http\Requests\UpdateAddressRequest;
use App\Http\Resources\AddressResource;
use App\Models\Address;
use App\Services\Api\AddressService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AddressController extends Controller
{
    //

    protected $addressService;
    public function __construct(AddressService $addressService)
    {
        $this->addressService = $addressService;
    }

    /**
     * Retrieves a list of addresses.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */

    public function index(Request $request)
    {
        try {
            $addresses = $this->addressService->index($request->all());
            return $this->success(AddressResource::collection($addresses), "Addresses Retrieved Successfully .", 200);
        } catch (\Exception $e) {
            Log::error($e);
            Log::error($e->getMessage());
            return $this->success(false, 'process failed try again later', 422);
        }
    }
    /**
     * Stores a new address.
     *
     * @param StoreAddressRequest $request
     * @return \Illuminate\Http\JsonResponse
     *
     * @throws \Exception
     */
    public function store(StoreAddressRequest $request)
    {
        try {
            $address = $this->addressService->store($request->validated());
            // dd($address);
            return $this->success(AddressResource::make($address), "Address Created Successfully .", 201);
        } catch (\Exception $e) {
            Log::error($e);
            Log::error($e->getMessage());
            return $this->success(false, 'process failed try again later', 422);
        }
    }
    /**
     * Updates an existing address.
     *
     * @param UpdateAddressRequest $request
     * @param Address $address
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @throws \Exception
     */
    public function update(UpdateAddressRequest $request, Address $address)
    {
        try {
            $address = $this->addressService->update($request->validated(), $address);
            return $this->success(AddressResource::make($address), "Address Updated Successfully .", 200);
        } catch (\Exception $e) {
            Log::error($e);
            Log::error($e->getMessage());
            return $this->success(false, 'process failed try again later', 422);
        }
    }
    public function destroy(Address $address)
    {
        try {
            $flag = $this->addressService->delete($address);
            if (!$flag) {
                return $this->success(null, "Address Not Found .", 404);
            }
            return $this->success(null, "Address Deleted Successfully .", 200);
        } catch (\Exception $e) {
            Log::error($e);
            Log::error($e->getMessage());
            return $this->success(false, 'process failed try again later', 422);
        }

    }
}


