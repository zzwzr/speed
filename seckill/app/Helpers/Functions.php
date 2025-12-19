<?php

function success($data = [], $message = 'success'): array
{
    return [
        'code'      => 0,
        'message'   => $message,
        'data'      => $data
    ];
}

function error($message = 'error', $data = []): array
{

    return [
        'code'      => 500,
        'message'   => $message,
        'data'      => $data
    ];
}

/**
 * 生成消息签名
 * @param array $message 消息内容
 * @param string $secret 双方约定的密钥
 * @return string
 */
function generateSign(array $message): string
{
    $secret = config('AMQP_MESSAGE_SECRET');

    unset($message['sign']);
    ksort($message);

    $stringToSign = http_build_query($message) . '&secret=' . $secret;

    return hash_hmac('sha256', $stringToSign, $secret);
}

/**
 * 验证消息签名
 * 
 * @param array $message 收到的消息
 * @param string $secret 双方约定的密钥
 * @return bool
 */
function verifySign(array $message): bool
{
    if (!isset($message['sign'])) {
        return false;
    }

    $receivedSign = $message['sign'];
    $calculatedSign = generateSign($message);

    return hash_equals($calculatedSign, $receivedSign);
}