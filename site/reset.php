<?php
require_once('usr.php');
setUid();
$chap = getParam();
if (!is_null($chap))
{
    require_once('fio.php');
    rDone($done);
    $rx = "/^$chap(?:\.|$)/";

    foreach (array_keys($done) as $k)
    {
        if (preg_match($rx, $k)) unset($done[$k]);
    }
    wDone($done);
}
goView($chap);
?>
