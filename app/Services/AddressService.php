<?php

namespace App\Services;


use App\Models\Address;
use Illuminate\Support\Facades\Auth;

class AddressService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function index(array $params = [])
    {
        //city_name
        //search
        //order_by
        //order_direction
        //per_page

        $query = Address::where('user_id', Auth::id());


        $willJoin = false;

        if (!empty($params['city_name'])) {
            $cityName = $params['city_name'];
            $query->whereHas('city', function ($q) use ($cityName) {
                $q->where('name->en', 'like', "%{$cityName}%")
                    ->orWhere('name->ar', 'like', "%{$cityName}%");
            });
        }
        if (isset($params['search'])) {
            $search = $params['search'];
            $query->where(function ($q) use ($search) {
                $q->where('street', 'like', "%{$search}%")
                    ->orWhere('details', 'like', "%{$search}%");
            });
        }
        if (!empty($params['city_name'])) {
            $cityName = $params['city_name'];
            $query->whereHas('city', function ($q) use ($cityName) {
                $q->where('name->en', 'like', "%{$cityName}%")
                    ->orWhere('name->ar', 'like', "%{$cityName}%");
            });
        }

        if (!empty($params['order_by'])) {
            $orderDir = $params['order_direction'] ?? 'desc';
            // Special-case sorting by city name
            if ($params['order_by'] === 'city_name') {
                // We'll join cities table and order by its JSON "name->en" (adjust if you want different locale)
                $willJoin = true;
                $query->join('cities', 'addresses.city_id', '=', 'cities.id')
                    ->select('addresses.*') // crucial so Eloquent hydrates Address models
                    ->orderBy('cities.name->en', $orderDir);
            } else {
                // Direct column on addresses table
                $query->orderBy($params['order_by'], $orderDir);
            }
        }

        if (!$willJoin) {
            $query->with('city');
        }

        $perPage = $params['per_page'] ?? 5;
        return $query->paginate($perPage);
    }
    public function store(array $data)
    {
        $address = Address::create([
            'user_id' => Auth::id(),
            'city_id' => $data['city_id'],
            'street' => $data['street'],
            'latitude' => $data['latitude'],
            'longitude' => $data['longitude'],
            'details' => $data['details'],
        ]);
        return $address;
    }
    public function update(array $data, Address $address)
    {
        $address->update($data);
        $address = Address::findOrFail($address->id);
        return $address;
    }
    public function delete(Address $address)
    {
        if (Auth::id() != $address->user_id) {
            return false;
        }
        $address->delete();
        return true;

    }
}
