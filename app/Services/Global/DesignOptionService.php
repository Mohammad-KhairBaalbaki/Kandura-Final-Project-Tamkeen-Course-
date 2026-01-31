<?php

namespace App\Services\Global;

use App\Models\DesignOption;
use Illuminate\Support\Facades\DB;

class DesignOptionService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function index()
    {
        return DB::transaction(function () {

            $designOptions = DesignOption::all();
            return $designOptions;
        });
    }

    public function store(array $data)
    {
        return DB::transaction(function () use ($data) {


            $designOption = DesignOption::create($data);
            return $designOption;
        });
    }

    public function update(array $data, DesignOption $designOption)
    {
        return DB::transaction(function () use ($data, $designOption) {


            $designOption->update($data);
            $designOption = DesignOption::findOrFail($designOption->id);
            return $designOption;
        });
    }

    public function delete(DesignOption $designOption)
    {
        return DB::transaction(function () use ($designOption) {

            
            $designOption->delete();
            return true;
        });
    }
}


