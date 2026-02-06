<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Services\Web\LanguageService;
use Illuminate\Support\Facades\Log;

class LanguageController extends Controller
{
    protected $languageService;

    public function __construct(LanguageService $languageService)
    {
        $this->languageService = $languageService;
    }

    /**
     * Switch the application language
     */
    public function switch($locale)
    {
        try {
            $this->languageService->switch($locale);

            return redirect()->back()->with('status', __('Language changed successfully'));
        } catch (\Exception $e) {
            Log::error($e);
            Log::error($e->getMessage());

            return $this->success(false, 'process failed try again later', 422);
        }
    }
}
