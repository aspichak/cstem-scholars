<?php

abstract class HTML
{
    public static function escape($value)
    {
        return htmlspecialchars($value, ENT_QUOTES, 'UTF-8', false);
    }

    public static function e($value)
    {
        return self::escape($value);
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
