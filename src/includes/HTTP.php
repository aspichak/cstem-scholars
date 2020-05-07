<?php

abstract class HTTP
{
    private static $flash = null;

    public static function isPost()
    {
        return $_SERVER['REQUEST_METHOD'] == 'POST';
    }

    public static function get($param = null, $default = null)
    {
        return self::filter($_GET, $param, $default);
    }

    public static function post($param = null, $default = null)
    {
        return self::filter($_POST, $param, $default);
    }

    public static function session($param = null, $default = null)
    {
        return self::filter($_SESSION, $param, $default);
    }

    public static function redirect($url, $flash = null)
    {
        if ($flash) {
            $_SESSION['flash'] = serialize($flash);
        }

        header("Location: $url");
        exit();
    }

    public static function error($body = 'Fatal error', $code = 400, $title = 'Error')
    {
        $_SESSION['errtitle'] = $title;
        $_SESSION['errbody'] = $body;
        $_SESSION['errcode'] = $code;

        // TODO: Move error page
        HTTP::redirect("/includes/errorPage/errorPage.php");
    }

    public static function flash()
    {
        if (isset($_SESSION['flash'])) {
            self::$flash = unserialize($_SESSION['flash']);
            $_SESSION['flash'] = null;
        }

        return self::$flash;
    }

    public static function url($path) {
        return BASE_URL . '/' . $path;
    }

    private static function filter($arr, $param, $default)
    {
        // Return the entire array if nothing was passed in
        if ($param === null) {
            return $arr;
        }

        if (is_array($param)) {
            // Remove extra columns
            $arr = array_intersect_key($arr, array_flip($param));

            // Add missing columns if necessary
            foreach ($param as $k) {
                if (!array_key_exists($k, $arr)) {
                    $arr[$k] = $default;
                }
            }

            return $arr;
        }

        return $arr[$param] ?? $default;
    }
}