<?php

namespace App\Utilities;

class Otp
{
    public static function verify($key, $code)
    {
        $binaryTimestamp = pack('N*', 0) . pack('N*', floor(microtime(true)/30));
        $hmac_hash = hash_hmac('sha1', $binaryTimestamp, Base32::decode($key), true);

        $offset = ord($hmac_hash[19]) & 0xf;

        $oneTimePassword = (
                ((ord($hmac_hash[$offset+0]) & 0x7f) << 24 ) |
                ((ord($hmac_hash[$offset+1]) & 0xff) << 16 ) |
                ((ord($hmac_hash[$offset+2]) & 0xff) << 8 ) |
                (ord($hmac_hash[$offset+3]) & 0xff)
            ) % pow(10, 6);

        return $oneTimePassword == $code;
    }
}