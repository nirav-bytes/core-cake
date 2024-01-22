<?php

namespace BytesNirav\CakeCorePhp\process\routes;

class CookieManager
{
    public static function setCookie($name, $value)
    {
        setcookie($name, $value, 0, "/");
    }

    public static function getCookie($name)
    {
        return isset($_COOKIE[$name]) ? $_COOKIE[$name] : null;
    }

    public static function deleteCookie($name)
    {
        if (isset($_COOKIE[$name])) {
            unset($_COOKIE[$name]);
            setcookie($name, null, -1, "/");
        }
    }
}
