<?php

namespace App\Services\Web;

use App\Models\Review;
use Illuminate\Support\Facades\DB;

class ReviewService
{
    public function index()
    {
        return DB::transaction(function () {
            return Review::with(['order', 'user'])
                ->latest()
                ->paginate(15);
        });
    }
}
