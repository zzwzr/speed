<?php

declare(strict_types=1);

namespace App\Service;

use App\Logger\UserLogger;

class NotificationService
{
    public function sendWelcomeNotification(int $userId): void
    {
        UserLogger::info($userId . '发送注册成功通知！');
    }
}
