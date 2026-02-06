<?php

namespace App\Services;

use App\Models\DeviceToken;
use App\Models\User;
use Kreait\Firebase\Contract\Messaging;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;

class FcmService
{
    public function __construct(private Messaging $messaging) {}

    public function sendToTokens(array $tokens, string $title, string $body, array $data = []): void
    {
        $tokens = array_values(array_unique(array_filter($tokens)));
        if (empty($tokens)) {
            return;
        }

        $message = CloudMessage::new()
            ->withNotification(Notification::create($title, $body))
            ->withData($data);

        // chunk by 500 tokens (safe)
        foreach (array_chunk($tokens, 500) as $chunk) {
            $report = $this->messaging->sendMulticast($message, $chunk);

            // تنظيف توكنات غير صالحة/غير معروفة للمشروع
            $invalid = $report->invalidTokens();
            $unknown = $report->unknownTokens();

            if ($invalid || $unknown) {
                DeviceToken::whereIn('token', array_merge($invalid, $unknown))->delete();
            }
        }
    }

    public function saveToken(User $user, string $token, ?string $platform = 'web'): DeviceToken
    {
        // token should be UNIQUE in DB
        return DeviceToken::updateOrCreate(
            ['token' => $token],
            ['user_id' => $user->id, 'platform' => $platform]
        );
    }

    public function deleteToken(string $token): void
    {
        DeviceToken::where('token', $token)->delete();
    }
}
