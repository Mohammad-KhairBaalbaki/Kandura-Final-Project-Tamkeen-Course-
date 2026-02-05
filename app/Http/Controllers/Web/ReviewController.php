<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Services\Web\ReviewService;
use Illuminate\Support\Facades\Log;

class ReviewController extends Controller
{
    public function __construct(private ReviewService $reviewService) {}

    public function index()
    {
        try {
            $reviews = $this->reviewService->index();
            return view('reviews.index', compact('reviews'));
        } catch (\Exception $e) {
            Log::error($e);
            Log::error($e->getMessage());
            return $this->success(false, 'process failed try again later', 422);
        }
    }
}
