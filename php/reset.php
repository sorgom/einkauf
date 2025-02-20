<?php
require_once('usr.php');
setusr();
$chap = getparam();
if ($chap)
{
    session_start();
    require_once('fio.php');
    $checks = getchecks();
    $rx = "/^$chap\./";

    foreach (array_keys($checks) as $k)
    {
        if (preg_match($rx, $k)) unset($checks[$k]);
    }
    wchecks($checks);
}
goview($chap);
?>
