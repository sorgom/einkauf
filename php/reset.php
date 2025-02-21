<?php
require_once('usr.php');
setusr();
$chap = getparam();
if ($chap)
{
    session_start();
    require_once('fio.php');
    $done = getdone();
    $rx = "/^$chap\./";

    foreach (array_keys($done) as $k)
    {
        if (preg_match($rx, $k)) unset($done[$k]);
    }
    wdone($done);
}
goview($chap);
?>
