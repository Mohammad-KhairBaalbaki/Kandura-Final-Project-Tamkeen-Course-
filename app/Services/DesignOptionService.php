<?php

namespace App\Services;

use App\Models\DesignOption;
use Illuminate\Support\Facades\Gate;

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
        $designOptions = DesignOption::all();
        return $designOptions;
    }

    public function store(array $data)
    {
        if(!Gate::allows('create-design-option', DesignOption::class))
            return false;
        $designOption = DesignOption::create($data);
        return $designOption;
    }

    public function update(array $data, DesignOption $designOption)
    {
        if(!Gate::allows('edit-design-option', DesignOption::class))
            return false;
        $designOption->update($data);
        $designOption = DesignOption::findOrFail($designOption->id);
        return $designOption;
    }

    public function delete(DesignOption $designOption)
    {
        if(!Gate::allows('delete-design-option', DesignOption::class))
            return false;
        $designOption->delete();
        return true;
    }
}
