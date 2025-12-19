<?php

declare(strict_types=1);

namespace App\Service;

use App\Logger\UserLogger;

class SmsService
{
    public function sendWelcomeSms(string $mobile): void
    {
        UserLogger::info($mobile . '发送注册成功短信！');
    }
}
