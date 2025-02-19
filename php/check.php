<?php
$x = $_POST['usr'];
$id = $_POST['id'];
$ck = $_POST['ck'];

require_once('usr.php');
if (isusr($x)) 
{
    $usr = $x;
    usrfiles();
    require_once('fio.php');

    $checks = getchecks();
    if ($ck == '1') $checks[$id] = true;
    else unset($checks[$id]);
    wchecks($checks);
}
?>