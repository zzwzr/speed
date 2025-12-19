<?php

declare(strict_types=1);

namespace App\Constants;

use Hyperf\Constants\Annotation\Constants;
use Hyperf\Constants\Annotation\Message;
use Hyperf\Constants\EnumConstantsTrait;

#[Constants]
enum ErrorCode: int
{
    use EnumConstantsTrait;
    #[Message("Server Error！")]
    case SERVER_ERROR = 10000;

    #[Message("用户不存在")]
    const USER_NOTFOUND = 10001;

    #[Message("密码错误")]
    const PASSWORD_ERROR = 10002;

    #[Message("签名错误")]
    const SIGN_ERROR = 10003;

    #[Message("订单创建失败")]
    const ORDER_ERROR = 10004;

}
