<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Services\Web\DashboardService;
use App\Services\Web\SettingsService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    protected $dashboardService;
    protected $settingsService;

    public function __construct(DashboardService $dashboardService, SettingsService $settingsService)
    {
        $this->dashboardService = $dashboardService;
        $this->settingsService = $settingsService;
    }

    public function index()
    {
        try {


            $data = $this->dashboardService->index();
            
            $user = Auth::user();
            if ($user) {
                $this->settingsService->syncFromDashboard($user);
            }

            return view('dashboard.home', $data);
        } catch (\Exception $e) {
            Log::error($e);
            Log::error($e->getMessage());
            return $this->success(false, 'process failed try again later', 422);
        }
    }
}
