<?php

abstract class HTML
{
    public static function escape($value)
    {
        return htmlspecialchars($value, ENT_QUOTES, 'UTF-8', false);
    }

    public static function tag($tag, $text = null, $attributes = [])
    {
        $attrs = '';

        foreach ($attributes as $k => $v) {
            $attrs .= is_string($k) ? " $k=\"$v\"" : " $v";
        }

        return ($text === null) ? "<{$tag}{$attrs} />" : "<{$tag}{$attrs}>$text</$tag>";
    }

    public static function link($url, $text, $attributes = [])
    {
        $attributes['href'] = $url;
        return self::tag('a', $text, $attributes);
    }

    public static function template($template, $v = [])
    {
        // TODO: constrain to "templates" folder
        // TODO: document security issues (directory traversal, variable overwriting)
        $layout = null;

        // Make a short alias to HTML::escape available to templates
        if (!function_exists('e')) {
            function e($value)
            {
                return HTML::escape($value);
            }
        }

        // Extract passed parameters into variables
        // WARNING: This may be dangerous. Exercise caution.
        if (is_array($v)) {
            extract($v, EXTR_OVERWRITE);
        }

        ob_start();
        include __DIR__ . '/../templates/' . $template;
        $content = ob_get_clean();

        if ($layout) {
            ob_start();
            include __DIR__ . '/../templates/' . $layout;
            return ob_get_clean();
        }

        return $content;
    }
}
