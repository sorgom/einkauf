<?php
require_once('usr.php');
setUid();
$chap = getParam();
if (!is_null($chap))
{
    require_once('data.php');
    rStates($states);
    $rx = "/^$chap(?:\.|$)/";

    foreach (array_keys($states) as $k)
    {
        if (preg_match($rx, $k)) unset($states[$k]);
    }
    wStates($states);
}
goView($chap);
?>
