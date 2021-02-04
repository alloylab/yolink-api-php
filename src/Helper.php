<?php

namespace YoLink;

class Helper
{
    public static function hashed_secKey($post_json, $SecKey)
    {
        $hash = strtolower(md5($post_json . $SecKey));

        return $hash;
    }

    public static function time_milliseconds()
    {
        $milliseconds = round(microtime(true) * 1000);

        return $milliseconds;
    }
}
