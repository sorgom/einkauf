<?php

function startlog()
{
    global $__log;
    $__log = fopen('data/tmp.txt', 'w');
}

function endlog()
{
    global $__log;
    fclose($__log);
}

function wlog($msg)
{
    global $__log;
    fwrite($__log, $msg . PHP_EOL);
}

?>