<?php
$x = $_POST['uid'];
$id = $_POST['id'];
$ck = $_POST['ck'];

require_once('usr.php');
if (isUid($x))
{
    $uid = $x;
    require_once('fio.php');

    rDone($done);

    if (! $ck)
    {
        unset($done[$id]);
        if (preg_match('/^(\d+)\./', $id, $m))
        {
            unset($done[$m[1]]);
        }
    }
    else $done[$id] = $ck;
    wDone($done);
}
?>
