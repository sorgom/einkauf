<?php
require_once('usr.php');
$chap = getparam();
if ($chap)
{
    session_start();
    setusr();
    require_once('fio.php');
    $checks = getchecks();
    $rx = "/^$chap\./";

    foreach (array_keys($checks) as $k)
    {
        if (preg_match($rx, $k)) unset($checks[$k]);
    }
    wchecks($checks);
}
go('view');
?>
