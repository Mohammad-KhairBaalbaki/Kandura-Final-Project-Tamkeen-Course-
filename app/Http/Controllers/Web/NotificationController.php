<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        try {
            $notifications = $request->user()
                ->notifications()
                ->latest()
                ->paginate(15)
                ->withQueryString();

            return view('notifications.index', compact('notifications'));
        } catch (\Exception $e) {
            Log::error($e);
            Log::error($e->getMessage());
            return $this->success(false, 'process failed try again later', 422);
        }
    }

    public function markRead(Request $request, string $id)
    {
        $notification = $request->user()
            ->notifications()
            ->where('id', $id)
            ->firstOrFail();

        if (is_null($notification->read_at)) {
            $notification->markAsRead();
        }

        $url = $notification->data['url'] ?? null;
        return $url ? redirect($url) : back();
    }

    public function markReadBulk(Request $request)
    {
        $ids = $request->input('notification_ids', []);

        if (!empty($ids)) {
            $request->user()
                ->unreadNotifications()
                ->whereIn('id', $ids)
                ->update(['read_at' => now()]);
        }

        return back();
    }
}
