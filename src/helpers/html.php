<?php

function e($text)
{
    return HTML::escape($text);
}

function tag($tag, $text = null, $attributes = [])
{
    return HTML::tag($tag, $text, $attributes);
}

function linkTo($url, $text, $attributes)
{
    return HTML::link($url, $text, $attributes);
}

function template($template, $v)
{
    return HTML::template($template, $v);
}

function url($path)
{
    return HTTP::url($path);
}
