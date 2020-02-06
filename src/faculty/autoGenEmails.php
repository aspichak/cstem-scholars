<?php

if(function_exists('mail'))
{
    echo "its there";
}
else
{
    echo ":(";
}
$temp = mail("colliennercovey@hotmail.com", "Subject", "Message");
var_dump($temp);
?>