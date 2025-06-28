<?php

function print_pre($var): void
{
    echo '<pre>' . print_r($var, 1) . '</pre>';
}

function esc($string): string
{
    return htmlspecialchars($string);
}
