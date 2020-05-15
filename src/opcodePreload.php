<?php

/**
 * Borrowed the preload function implementation from
 * Dmitry Stogov's RFC https://wiki.php.net/rfc/preload
 */
function preload($preload, array $ignore = [], string $pattern = "/\.php$/")
{
    if (is_array($preload)) {
        foreach ($preload as $path) {
            preload($path, $ignore, $pattern);
        }
    } else {
        if (is_string($preload)) {
            $path = $preload;
            if (!in_array($path, $ignore)) {
                if (is_dir($path)) {
                    if ($dh = opendir($path)) {
                        while (($file = readdir($dh)) !== false) {
                            if ($file !== "." && $file !== "..") {
                                preload($path . "/" . $file, $ignore, $pattern);
                            }
                        }
                        closedir($dh);
                    }
                } else {
                    if (is_file($path) && preg_match($pattern, $path)) {
                        if (!opcache_compile_file($path)) {
                            trigger_error("Preloading Failed", E_USER_ERROR);
                        }
                    }
                }
            }
        }
    }
}

preload(
    [
        __DIR__ . '/vendor/apereo/phpcas',
        __DIR__ . '/respect',
        __DIR__ . '/vendor/phpmailer/phpmailer',
        __DIR__ . '/vendor/symfony/polyfill-mbstring',
    ],
    [
        __DIR__ . '/vendor/symfony/polyfill-mbstring/bootstrap.php',
    ]
);