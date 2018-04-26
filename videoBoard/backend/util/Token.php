<?php
namespace backend\util;

use Yii;

class Token
{
    public static function putToken($user, $expire, $tokenName)
    {
        $redis = Yii::$app->redis;

        if (isset($cookies[$tokenName])) {
            unset($_COOKIE[$tokenName]);
        }

        if ($redis->get($tokenName) !== null) {
            $redis->del($tokenName);
        }
        $token = Token::getToken($user);
        $redis->setex($token, $expire, serialize($user));
        setcookie($tokenName, $token, time() + $expire, '/', 'www.api.isabellali.videoboard.com');
    }

    public static function getToken($user)
    {
        return md5($user->id . date("Ymd").time().rand(1000, 9999));
    }

    public static function getTokenValue($token)
    {
        $redis = Yii::$app->redis;
        $user = $redis->get($token);
        if ($user) {
            $user = unserialize($user);
        }
        return $user;
    }
}