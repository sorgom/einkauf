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

    $done = getdone();
    if ($ck == '1') $done[$id] = true;
    else
    {
        unset($done[$id]);
        if (preg_match('/^(\d+)\./', $id, $m))
        {
            unset($done[$m[1]]);
        }
    }
    wdone($done);
}
?>
