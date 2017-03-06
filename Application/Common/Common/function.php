<?php
/**
 * Created by PhpStorm.
 * User: janke
 * Date: 17-3-6
 * Time: 上午11:04
 */
function get_salt()
{
    date_default_timezone_set('PRC');
    return date('Y-m-d H:i:s');
}

function my_echo($msg)
{
    echo my_encode($msg);
}

function my_md5($data, $salt)
{
    return md5($data.$salt);
}

function my_encode($message)
{
    return json_encode($message, JSON_UNESCAPED_UNICODE);
}

function get_uuid($prefix='')
{
    $str = md5(microtime());
    $prefix.= substr($str, 0, 8).'-';
    $prefix.= substr($str, 8, 4).'-';
    $prefix.= substr($str, 12, 4).'-';
    $prefix.= substr($str, 16, 4).'-';
    $prefix.= substr($str, 20, 12);
    return $prefix;
}