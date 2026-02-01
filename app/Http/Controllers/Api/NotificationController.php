<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\NotificationResource;
use App\Services\Api\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class NotificationController extends Controller
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    public function index(Request $request)
    {
        try {
        $paginator = $this->notificationService->index($request->query());

        $message = ($paginator->total() === 0)
            ? 'No notifications yet.'
            : 'Notifications Retrieved Successfully.';

        return $this->success(
            NotificationResource::collection($paginator),
            $message,
            200
        );

    } catch (\Exception $e) {
        Log::error($e);
        Log::error($e->getMessage());
        return $this->success(false, 'process failed try again later', 422);
    }
    }

    public function markRead(Request $request, string $notification)
    {
        try {
            $n = $this->notificationService->markRead($notification);
            return $this->success(NotificationResource::make($n), "Notification Marked As Read .", 200);
        } catch (\Exception $e) {
            Log::error($e);
            Log::error($e->getMessage());
            return $this->success(false, 'process failed try again later', 422);
        }
    }

    public function markAllRead(Request $request)
    {
        try {
            $updated = $this->notificationService->markAllRead();
            return $this->success(['marked_count' => $updated], "Notifications Marked As Read .", 200);
        } catch (\Exception $e) {
            Log::error($e);
            Log::error($e->getMessage());
            return $this->success(false, 'process failed try again later', 422);
        }
    }

    public function unread(Request $request)
    {
        try {
            $notifications = $this->notificationService->unread($request->query());
            return $this->success(NotificationResource::collection($notifications), "Unread Notifications Retrieved Successfully (Total : " . $notifications->count() . ") .", 200);
        } catch (\Exception $e) {
            Log::error($e);
            Log::error($e->getMessage());
            return $this->success(false, 'process failed try again later', 422);
        }
    }

    public function unreadCount(Request $request)
    {
        try {
            $count = $this->notificationService->unreadCount();
            return $this->success(['total' => $count], "Unread Notifications Count Retrieved Successfully .", 200);
        } catch (\Exception $e) {
            Log::error($e);
            Log::error($e->getMessage());
            return $this->success(false, 'process failed try again later', 422);
        }
    }
}
